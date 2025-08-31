<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function create()
    {
        // Return the correct view for creating a support ticket
        return view('create-support-ticket');
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

        if(!$ticket){
            return redirect()->back()->with('error', 'Failed to create ticket. Please try again.');
        }

        return redirect()->route('tickets.show', $ticket->id)
                 ->with('success', 'Ticket created!');

    }

    public function show(Ticket $ticket)
    {
        return view('tickets.show', compact('ticket'));
    }
}
