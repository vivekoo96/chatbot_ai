<?php

namespace App\Livewire\Admin;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UserManagement extends Component
{
    public $search = '';

    public function impersonate($userId)
    {
        $user = User::findOrFail($userId);
        $originalId = Auth::id();

        // Prevent self-impersonation (though UI should prevent it)
        if ($user->id === $originalId) {
            session()->flash('error', 'You are already logged in as this user!');
            return;
        }

        // Store original admin ID in session
        session(['admin_impersonator_id' => $originalId]);

        // Mark as verified so admin is not redirected to verification page
        if (!$user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            $user->save();
        }

        // Login as the user
        Auth::login($user);

        return redirect()->route('dashboard');
    }

    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);

        // Prevent user from deleting themselves
        if ($user->id === Auth::id()) {
            session()->flash('error', 'You cannot delete yourself!');
            return;
        }

        $user->chatbots()->delete(); // Cascade delete chatbots
        $user->delete();
        session()->flash('message', 'User deleted successfully!');
    }

    public function render()
    {
        $users = User::with(['plan', 'chatbots'])
            ->withCount('chatbots')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('created_at', 'desc')
            ->get();

        return view('livewire.admin.user-management', [
            'users' => $users,
        ]);
    }
}

