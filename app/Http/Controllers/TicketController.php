<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function create()
    {
        $assetsArray = []; // or pull from model if editing
        return view('create-support-ticket', compact('assetsArray'));
        
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'status' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|string',
            'stakeholders.*' => 'integer|exists:users,id',
            'assignee' => 'nullable|integer|exists:users,id',
            'tshirt_size' => 'nullable|string',
            'assets.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx'
        ]);

        $data['stakeholders'] = json_encode($data['stakeholders'] ?? []);

        $assets = [];
        if ($request->hasFile('assets')) {
            foreach ($request->file('assets') as $file) {
                $assets[] = $file->store('tickets_assets', 'public');
            }
        }
        $data['assets'] = json_encode($assets);

        $ticket = Ticket::create($data);

        if (!$ticket) {
            return redirect()->back()->with('error', 'Failed to create ticket. Please try again.');
        }

        return redirect()->route('tickets.show', $ticket->id)
            ->with('success', 'Ticket created!');
    }

    public function show(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }

    public function search(Request $request)
    {
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

        $tickets = $query->latest()->paginate(10)->withQueryString();

        return view('tickets.search', compact('tickets'));
    }

    public function destroy($id)
    {
        $ticket = Ticket::findOrFail($id);
        $ticket->delete();

        return redirect()->route('tickets.search')->with('success', 'Ticket deleted successfully.');
    }

   

    public function update(Request $request, $id)
    {
        $ticket = Ticket::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|string',
            'stakeholders.*' => 'integer|exists:users,id',
            'assignee' => 'nullable|integer|exists:users,id',
            'tshirt_size' => 'nullable|string',
            'assets.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx'
        ]);

        dd($validated);

        // Handle new asset uploads
        $newAssets = [];
        if ($request->hasFile('assets')) {
            foreach ($request->file('assets') as $file) {
                $path = $file->store('tickets/assets', 'public');
                $newAssets[] = $path;
            }
        }

        // Merge with existing assets
        $existingAssets = json_decode($ticket->assets, true) ?? [];
        $mergedAssets = array_merge($existingAssets, $newAssets);

        // Update ticket fields
        $ticket->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'assets' => json_encode($mergedAssets),
            'status' => $validated['status'] ?? $ticket->status,
            'priority' => $validated['priority'] ?? $ticket->priority,
            'tshirt_size' => $validated['tshirt_size'] ?? $ticket->tshirt_size,
            'assignee' => $validated['assignee'] ?? $ticket->assignee,
            'stakeholders' => json_encode($validated['stakeholders'] ?? []),
        ]);

        return redirect()->route('tickets.show', $ticket->id)->with('success', 'Ticket updated successfully.');
    }
}
