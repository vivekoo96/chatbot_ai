<?php

namespace App\Livewire\Dashboard;

use App\Models\TeamMember;
use App\Models\User;
use App\Notifications\TeamInvitationNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class TeamManagement extends Component
{
    public $name = '';
    public $email = '';
    public $role = 'member';
    public $search = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:team_members,email',
        'role' => 'required|in:admin,member,viewer',
    ];

    public function invite()
    {
        $user = Auth::user();
        if (!$user->isTeamAdmin()) {
            session()->flash('error', 'Only team administrators can invite members.');
            return;
        }

        $owner = $user->getOwner();

        // Check if owner has reached team limit
        if ($owner->hasReachedTeamLimit()) {
            session()->flash('error', 'You have reached your team member limit. Please upgrade your plan.');
            return;
        }

        // Validate
        $this->validate();

        // Enforce role based on plan access
        if (!$owner->plan?->has_role_based_access) {
            $this->role = 'member';
        }

        // Generate a random secure password
        $plainPassword = Str::random(12);

        // 1. Check if a User with this email already exists
        $user = User::where('email', $this->email)->first();

        if (!$user) {
            // 2. Create a new User account
            $user = User::create([
                'name' => $this->name,
                'email' => $this->email,
                'password' => Hash::make($plainPassword),
                'invited_by_user_id' => $owner->id,
                'email_verified_at' => now(), // Auto-verify for now
            ]);
        } else {
            // 3. Update existing user's invited_by link if not set
            // Also update password so they can log in if they didn't have an account
            if (!$user->invited_by_user_id && !$user->is_super_admin && $user->id !== $owner->id) {
                $user->update([
                    'invited_by_user_id' => $owner->id,
                    'password' => Hash::make($plainPassword),
                ]);
            } else {
                // If they already have an account but no password set (somehow) or we just want to reset it for the invite
                $user->update(['password' => Hash::make($plainPassword)]);
            }
        }

        // Send Email Notification
        try {
            $user->notify(new TeamInvitationNotification(
                $owner->name,
                $owner->business_name ?? 'Hemnix Assist',
                $this->email,
                $plainPassword
            ));
        } catch (\Exception $e) {
            \Log::error('Failed to send team invitation email: ' . $e->getMessage());
        }

        // 4. Create TeamMember record
        TeamMember::create([
            'user_id' => $owner->id,
            'name' => $this->name,
            'email' => $this->email,
            'role' => $this->role,
            'invitation_token' => TeamMember::generateInvitationToken(),
            'invitation_sent_at' => now(),
            'is_active' => true,
        ]);

        $this->reset(['name', 'email', 'role']);
        session()->flash('message', 'Team member invited successfully! An email has been sent to them with their login details.');
    }

    public function removeMember($memberId)
    {
        $user = Auth::user();
        if (!$user->isTeamAdmin()) {
            session()->flash('error', 'Only team administrators can remove members.');
            return;
        }

        $owner = $user->getOwner();
        $member = TeamMember::where('user_id', $owner->id)->findOrFail($memberId);
        $member->delete();
        session()->flash('message', 'Member removed successfully!');
    }

    public function updateRole($memberId, $newRole)
    {
        $user = Auth::user();
        if (!$user->isTeamAdmin()) {
            session()->flash('error', 'Only team administrators can update roles.');
            return;
        }

        $owner = $user->getOwner();

        if (!$owner->plan?->has_role_based_access) {
            session()->flash('error', 'Role-based access is not available on your current plan.');
            return;
        }

        $member = TeamMember::where('user_id', $owner->id)->findOrFail($memberId);
        $member->update(['role' => $newRole]);
        session()->flash('message', 'Role updated successfully!');
    }

    public function render()
    {
        $user = Auth::user();
        $owner = $user->getOwner();

        $members = TeamMember::where('user_id', $owner->id)
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->get();

        return view('livewire.dashboard.team-management', [
            'members' => $members,
            'reachedLimit' => $owner->hasReachedTeamLimit(),
            'canAssignRoles' => $owner->plan?->has_role_based_access ?? false,
            'maxMembers' => $owner->plan?->max_team_users ?? 0,
            'currentCount' => $members->count(),
        ]);
    }
}
