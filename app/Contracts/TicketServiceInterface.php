<?php

namespace App\Contracts;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Contracts\Pagination\Paginator;

interface TicketServiceInterface
{
    public function create(array $data, Request $request): int;

    public function display(Ticket $ticket): array;

    public function ticketSearch(Request $request): Paginator;

    public function delete(int $ticketId): int;

    public function update(Request $request,array $validated,int $id): int;

    

}
