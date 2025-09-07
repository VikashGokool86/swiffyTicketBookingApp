<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Contracts\TicketServiceInterface;

class TicketController extends Controller
{
    public function create(): View 
    {
        $stakeholders = User::all() ?? []; 
        $assetsArray = []; // Initialize an empty array for assets
        return view('create-support-ticket', compact('assetsArray','stakeholders'));
    }

    public function store(Request $request): Redirector|RedirectResponse
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

        $ticketService = resolve(TicketServiceInterface::class);
        $ticketID = $ticketService->create($data, $request);
        if (!$ticketID) {
            return redirect()->back()->with('error', 'Failed to create ticket. Please try again.');
        }

        return redirect()->route('tickets.show', $ticketID)
            ->with('success', 'Ticket created!');
    }

    public function show(Ticket $ticket): View
    {
        // troubleshoot why stakeholders are not showing after a save
        
        $ticketService = resolve(TicketServiceInterface::class);
        $stakeholderInfo = $ticketService->display($ticket);

        return view('tickets.show', [
            'ticket' => $ticket,
            'preselectedStakeholders' => $stakeholderInfo['preselectedStakeholders'] ?? [],
            'stakeholderIds' => $stakeholderInfo['stakeholderIds'] ?? [],
        ]);
    }

    public function search(Request $request): View
    {
        $ticketService = resolve(TicketServiceInterface::class);
        $tickets = $ticketService->ticketSearch($request);
        return view('tickets.search', compact('tickets'));
    }

    public function destroy($id): Redirector|RedirectResponse
    {
        $ticketService = resolve(TicketServiceInterface::class);
        $ticketResponse = $ticketService->delete($id);
        if(!$ticketResponse){
            return redirect()->back()->with('error', 'Failed to delete ticket. Please try again.');
        }
        return redirect()->route('tickets.search')->with('success', 'Ticket deleted successfully.');
    }

    public function update(Request $request, $id): Redirector|RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'priority' => 'required|string',
            'stakeholders.*' => 'integer|exists:users,id',
            'assignee' => 'nullable|integer|exists:users,id',
            'tshirt_size' => 'nullable|string',
            'assets.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,docx',
            'existing_assets' => 'nullable|string' 
        ]);

        $ticketService = resolve(TicketServiceInterface::class);
        $ticketID = $ticketService->update($request, $validated, $id);
        if(!$ticketID) {
            return redirect()->back()->with('error', 'Failed to update ticket. Please try again.');
        }
        return redirect()->route('tickets.show', $ticketID)->with('success', 'Ticket updated successfully.');
    }
}
