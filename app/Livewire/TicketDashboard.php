<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Ticket;
use Livewire\Component;
use Illuminate\Support\Facades\DB;


class TicketDashboard extends Component
{
    public $statusData;
    public $assigneeData;
    public $recentTickets;
    public $highPriority;

    public function mount()
    {
        $this->statusData = $this->getStatusData();
        $this->assigneeData = $this->getAssigneeData();
        $this->recentTickets = Ticket::latest()->take(10)->get();
        $this->highPriority = Ticket::where('priority', 'H')->whereNull('assignee')->limit(10)->get();
    }

    public function getStatusData()
    {
        $statuses = Ticket::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')->pluck('total', 'status');

        $labelMap = [
            'O' => 'Open',
            'P' => 'In Progress',
            'B' => 'Blocked',
            'C' => 'Closed',
        ];

        return [
            'labels' => collect($statuses->keys())->map(fn($key) => $labelMap[$key] ?? $key)->toArray(),
            'values' => $statuses->values()->toArray(),
        ];

    }

    public function getAssigneeData()
    {
        $users = User::withCount([
            'assignedTickets',
            'completedTickets' => fn($q) => $q->where('status', 'Closed')
        ])->get();

        return [
            'labels' => $users->pluck('name')->toArray(),
            'values' => $users->pluck('completed_tickets_count')->toArray(),
        ];
    }

    public function render()
    {
        return view('livewire.ticket-dashboard');
    }

}
