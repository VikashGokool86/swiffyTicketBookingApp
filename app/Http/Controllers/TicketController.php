<?php

namespace App\Http\Controllers;

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
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|string',
            'stakeholders' => 'nullable|string',
            'tshirt_size' => 'nullable|string',
            'assets.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx'
        ]);

        $assets = [];
        if ($request->hasFile('assets')) {
            foreach ($request->file('assets') as $file) {
                $assets[] = $file->store('tickets_assets', 'public');
            }
        }
        $data['assets'] = json_encode($assets);

        \App\Models\Ticket::create($data);

        return redirect()->back()->with('success', 'Ticket created!');
    }
}
