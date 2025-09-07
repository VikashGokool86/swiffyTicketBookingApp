<div>
    <div class="space-y-8 p-6 bg-gray-50 min-h-screen">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-lg font-semibold mb-4">📊 Tickets by Status</h2>
                <canvas id="statusChart"></canvas>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-lg font-semibold mb-4">👤 Completion by Assignee</h2>
                <canvas id="assigneeChart"></canvas>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-lg font-semibold mb-2">🕒 Recent Tickets</h2>
                <ul class="space-y-1 text-sm">
                    @php
                    $statusLabels = [
                    'O' => 'Open',
                    'P' => 'In Progress',
                    'B' => 'Blocked',
                    'C' => 'Closed',
                    ];
                    @endphp
                    @foreach($recentTickets as $ticket)
                    <a href="{{ route('tickets.show', $ticket->id) }}" class="flex items-center gap-2 text-blue-600 hover:underline text-sm">
                        ➖ {{ $ticket->title }} — {{ $statusLabels[$ticket->status] ?? '' }}
                    </a>
                    @endforeach
                </ul>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <h2 class="text-lg font-semibold mb-2">🔥 High Priority Unassigned</h2>
                <ul class="space-y-1 text-sm">
                    @foreach($highPriority as $ticket)
                    <a href="{{ route('tickets.show', $ticket->id) }}" class="flex items-center gap-2 text-blue-600 hover:underline text-sm">
                        ➖ {{ $ticket->title }}
                    </a>
                    @endforeach
                </ul>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const statusCtx = document.getElementById('statusChart').getContext('2d');
            new Chart(statusCtx, {
                type: 'bar',
                data: {
                    labels: @json($statusData['labels']),
                    datasets: [{
                        label: 'Tickets',
                        data: @json($statusData['values']),
                        backgroundColor: '#4ade80'
                    }]
                }
            });

            const assigneeCtx = document.getElementById('assigneeChart').getContext('2d');
            new Chart(assigneeCtx, {
                type: 'bar',
                data: {
                    labels: @json($assigneeData['labels']),
                    datasets: [{
                        label: 'Completed Tickets',
                        data: @json($assigneeData['values']),
                        backgroundColor: '#60a5fa'
                    }]
                }
            });
        </script>
    </div>
</div>