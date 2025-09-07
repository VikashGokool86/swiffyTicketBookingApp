<h2>🔄 Ticket Updated</h2>
<p>The following ticket has been updated: <a href="{{ route('tickets.show', $ticket->id) }}">View Ticket</a></p>

<ul>
    <li><strong>Title:</strong> {{ $ticket->title }}</li>
    <li><strong>Status:</strong> {{ $ticket->status }}</li>
    <li><strong>Priority:</strong> {{ ucfirst($ticket->priority) }}</li>
</ul>

<p>Changes have been made. Please review the latest details.</p>