<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class ActivityFeed extends Component
{
    public function render()
    {
        $activities = auth()->user()->getOwner()->activities()
            ->with('chatbot')
            ->latest()
            ->take(10)
            ->get();

        return view('livewire.dashboard.activity-feed', [
            'activities' => $activities
        ]);
    }
}
