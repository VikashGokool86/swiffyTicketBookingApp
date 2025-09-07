<?php

namespace App\Services;

use Exception;
use App\Models\User;
use App\Models\Ticket;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TicketService
{
    public function create(array $data, Request $request): int
    {
        try {
           $data['stakeholders'] = json_encode($data['stakeholders'] ?? []);

        $assets = [];
        if ($request->hasFile('assets')) {
            foreach ($request->file('assets') as $file) {
                $assets[] = $file->store('tickets_assets', 'public');
            }
        }
        $data['assets'] = json_encode($assets);

        $ticket = Ticket::create($data);
        return (!$ticket) ? 0 : $ticket->id;
        } catch (Exception $e) {
            Log::error('Error creating ticket: ' . $e->getMessage());
            return 0;
        }
    }

    public function display(Ticket $ticket): array
    {
        try {
            $stakeholderIds = json_decode($ticket->stakeholders ?? '[]');
            // Fetch preselected stakeholders
            $preselectedStakeholders = User::whereIn('id', $stakeholderIds)
                ->get(['id', 'name'])
                ->map(fn($user) => ['id' => $user->id, 'name' => $user->name])
                ->values()
                ->toArray();

            return [
                'preselectedStakeholders' => $preselectedStakeholders,
                'stakeholderIds' => $stakeholderIds,
            ];
        } catch (Exception $e) {
            Log::error('Error loading ticket assignee: ' . $e->getMessage());
            return [];
        }
       
    }

    public function ticketSearch(Request $request): Paginator
    {
        try {
            $request->validate([
                'ticket_number' => 'nullable|integer|exists:tickets,id',
                'assignee' => 'nullable|integer|exists:users,id',
                'stakeholder' => 'nullable|integer|exists:users,id',
            ]);

            $query = Ticket::query();

            if ($request->filled('ticket_number')) {
                $query->where('id', $request->ticket_number);
            }

            if ($request->filled('assignee')) {
                $query->where('assignee', $request->assignee);
            }

            if ($request->filled('stakeholder')) {
                $query->whereJsonContains('stakeholders', $request->stakeholder);
            }

            return $query->latest()->paginate(10)->withQueryString();

        } catch (Exception $e) {
            Log::error('Validation error in ticket search: ' . $e->getMessage());
            // Return an empty paginator on validation failure
            return Ticket::whereRaw('1 = 0')->paginate(10);
        }
    }

    public function delete(int $ticketId): int
    {
        try {
            $ticket = Ticket::findOrFail($ticketId);
            if ($ticket->delete()) {
                Log::info('Ticket ID ' . $ticketId . ' deleted successfully.');
                return true;
            } else {
                Log::warning('Failed to delete ticket ID ' . $ticketId);
                return false;
            }
        } catch (Exception $e) {
            Log::error('Error deleting ticket ID ' . $ticketId . ': ' . $e->getMessage());
            return false;
        }
        
    }

    public function update(Request $request,array $validated,int $id): int
    { 
        try {
            $ticket = Ticket::findOrFail($id);

            // Parse existing assets from hidden input
            $existingAssets = array_filter(explode(',', $request->input('existing_assets', '')));

            // Handle new asset uploads
            $newAssets = [];
            if ($request->hasFile('assets')) {
                foreach ($request->file('assets') as $file) {
                    $path = $file->store('tickets/assets', 'public');
                    $newAssets[] = $path;
                }
            }

            // Final asset list = kept + newly uploaded
            $mergedAssets = array_merge($existingAssets, $newAssets);
            // Update ticket fields
            $ticket->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'assets' => json_encode($mergedAssets),
                'status' => $validated['status'],
                'priority' => $validated['priority'],
                'tshirt_size' => $validated['tshirt_size'] ?? null,
                'assignee' => $validated['assignee'] ?? null,
                'stakeholders' => json_encode($validated['stakeholders'] ?? []),
            ]);
            return (!$ticket) ? 0 : $ticket->id;
        } catch (Exception $e) {
            Log::error('Error updating ticket ID ' . $id . ': ' . $e->getMessage());
            return 0;
        }
       
    }

}
