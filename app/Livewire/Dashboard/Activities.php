<?php

namespace App\Livewire\Dashboard;

use App\Models\Activity;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class Activities extends Component
{
    use WithPagination;

    public $search = '';
    public $eventTypeFilter = 'all';
    public $dateFrom = '';
    public $dateTo = '';
    public $autoRefresh = true;
    public $perPage = 25;

    protected $queryString = [
        'search' => ['except' => ''],
        'eventTypeFilter' => ['except' => 'all'],
        'dateFrom' => ['except' => ''],
        'dateTo' => ['except' => ''],
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingEventTypeFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFrom()
    {
        $this->resetPage();
    }

    public function updatingDateTo()
    {
        $this->resetPage();
    }

    public function toggleAutoRefresh()
    {
        $this->autoRefresh = !$this->autoRefresh;
    }

    public function clearFilters()
    {
        $this->search = '';
        $this->eventTypeFilter = 'all';
        $this->dateFrom = '';
        $this->dateTo = '';
        $this->resetPage();
    }

    public function exportActivities()
    {
        $activities = $this->getFilteredActivities()->get();

        $filename = 'activities_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function () use ($activities) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Event Type', 'Chatbot', 'Visitor', 'Message', 'Timestamp']);

            foreach ($activities as $activity) {
                fputcsv($file, [
                    $activity->type,
                    $activity->chatbot?->name ?? 'N/A',
                    $activity->message,
                    $activity->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    private function getFilteredActivities()
    {
        $owner = Auth::user()->getOwner();
        $query = Activity::with(['chatbot'])
            ->where('user_id', $owner->id)
            ->orderBy('created_at', 'desc');

        // Search filter
        if (!empty($this->search)) {
            $query->where(function ($q) {
                $q->where('message', 'like', '%' . $this->search . '%')
                    ->orWhereHas('chatbot', function ($chatbotQuery) {
                        $chatbotQuery->where('name', 'like', '%' . $this->search . '%');
                    });
            });
        }

        // Event type filter
        if ($this->eventTypeFilter !== 'all') {
            $query->where('type', $this->eventTypeFilter);
        }

        // Date range filter
        if (!empty($this->dateFrom)) {
            $query->whereDate('created_at', '>=', $this->dateFrom);
        }

        if (!empty($this->dateTo)) {
            $query->whereDate('created_at', '<=', $this->dateTo);
        }

        return $query;
    }

    public function getStatsProperty()
    {
        $owner = Auth::user()->getOwner();
        $ownerId = $owner->id;

        return [
            'total' => Activity::where('user_id', $ownerId)->count(),
            'today' => Activity::where('user_id', $ownerId)
                ->whereDate('created_at', today())
                ->count(),
            'messages' => Activity::where('user_id', $ownerId)
                ->where('type', 'message_sent')
                ->whereDate('created_at', today())
                ->count(),
            'leads' => Activity::where('user_id', $ownerId)
                ->where('type', 'lead_captured')
                ->count(),
        ];
    }

    public function render()
    {
        $activities = $this->getFilteredActivities()->paginate($this->perPage);

        return view('livewire.dashboard.activities', [
            'activities' => $activities,
            'stats' => $this->stats,
        ]);
    }
}
