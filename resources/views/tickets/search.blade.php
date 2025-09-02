<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold text-gray-800">Search Tickets</h2>
    </x-slot>

    <div class="px-10 py-6">
        {{-- Search Form --}}
        <form method="GET" action="{{ route('tickets.search') }}" class="mb-6 bg-white p-6 rounded shadow flex gap-4 items-end">
            <div>
                <label class="block text-sm font-medium mb-1">Ticket Number</label>
                <input type="number" name="ticket_number" class="border rounded px-3 py-2 w-48" value="{{ request('ticket_number') }}">
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Assignee</label>
                <select name="assignee" class="border rounded px-3 py-2 w-48">
                    <option value="">-- Select Assignee --</option>
                    @foreach(\App\Models\User::all() as $user)
                    <option value="{{ $user->id }}" {{ request('assignee') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium mb-1">Stakeholder</label>
                <select name="stakeholder" class="border rounded px-3 py-2 w-48">
                    <option value="">-- Select Stakeholder --</option>
                    @foreach(\App\Models\User::all() as $user)
                    <option value="{{ $user->id }}" {{ request('stakeholder') == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">
                Search
            </button>
            <a href="{{ route('tickets.search') }}"
                class="bg-gray-300 text-gray-800 px-4 py-2 rounded hover:bg-gray-400 transition">
                Clear Filters
            </a>

        </form>

        {{-- Active Filters Summary --}}
        @if(request()->anyFilled(['ticket_number', 'assignee', 'stakeholder', 'status', 'priority']))
        <div class="mb-4 text-sm text-gray-600">
            <strong>Filters:</strong>
            @foreach(request()->except('page') as $key => $value)
            <span class="bg-gray-200 px-2 py-1 rounded mr-2">{{ ucfirst($key) }}: {{ $value }}</span>
            @endforeach
        </div>
        @endif

        {{-- Ticket Results --}}
        @if($tickets->count())
        <table class="w-full table-auto bg-white rounded shadow">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-2 text-left">Ticket #</th>
                    <th class="px-4 py-2 text-left">Status</th>
                    <th class="px-4 py-2 text-left">Title</th>
                    <th class="px-4 py-2 text-left">Assignee</th>
                    <th class="px-4 py-2 text-left">Created</th>
                    <th class="px-4 py-2 text-left">Actions</th>
                </tr>
            </thead>
            @php
            $statusMap = [
            'O' => ['label' => 'Open', 'class' => 'bg-green-400 text-white'],
            'C' => ['label' => 'Closed', 'class' => 'bg-black text-white'],
            'B' => ['label' => 'Blocked', 'class' => 'bg-red-600 text-white'],
            'P' => ['label' => 'In Progress', 'class' => 'bg-orange-400'],
            ];
            @endphp

            <tbody>
                @foreach($tickets as $ticket)
                @php
                $statusInfo = $statusMap[$ticket->status] ?? ['label' => $ticket->status, 'class' => 'bg-white'];
                @endphp

                <tr class="border-b {{ $statusInfo['class'] }}">
                    <td class="px-4 py-2">{{ $ticket->id }}</td>
                    <td class="px-4 py-2 font-semibold">{{ $statusInfo['label'] }}</td>
                    <td class="px-4 py-2">{{ $ticket->title }}</td>
                    <td class="px-4 py-2">
                        {{ optional(\App\Models\User::find($ticket->assignee))->name ?? 'Unassigned' }}
                    </td>
                    <td class="px-4 py-2">{{ $ticket->created_at->diffForHumans() }}</td>
                    <td class="px-4 py-2">
                        <a href="{{ route('tickets.show', $ticket->id) }}" class="text-blue-600 hover:underline">View</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-6">
            {{ $tickets->links() }}
        </div>
        @else
        <div class="text-gray-600 mt-6">No tickets found for your search.</div>
        @endif
    </div>


</x-app-layout>