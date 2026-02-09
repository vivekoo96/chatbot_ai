<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Models\Conversation;

class Leads extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $user = Auth::user();
        $owner = $user->getOwner();

        $leads = Conversation::whereIn('chatbot_id', $owner->chatbots()->pluck('id'))
            ->whereNotNull('visitor_phone')
            ->where('visitor_phone', '!=', '')
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('visitor_name', 'like', '%' . $this->search . '%')
                        ->orWhere('visitor_phone', 'like', '%' . $this->search . '%')
                        ->orWhere('visitor_email', 'like', '%' . $this->search . '%');
                });
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        return view('livewire.dashboard.leads', [
            'leads' => $leads
        ])->layout('layouts.app');
    }
}
