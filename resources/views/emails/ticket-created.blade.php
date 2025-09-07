<h2>🎫 New Ticket Created</h2>
<p>A new support ticket has been created: <a href="{{ route('tickets.show', $ticket->id) }}">View Ticket</a></p>


<ul>
    <li><strong>Title:</strong> {{ $ticket->title }}</li>
    <li><strong>Description:</strong> {{ $ticket->description }}</li>
    <li><strong>Priority:</strong> {{ ucfirst($ticket->priority) }}</li>
    <li><strong>Status:</strong> {{ $ticket->status }}</li>
</ul>

<p>You’ve been assigned to this ticket. Please review and take action.</p>