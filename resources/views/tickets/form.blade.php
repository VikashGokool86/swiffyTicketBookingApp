<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ isset($ticket) ? 'View Support Ticket' : 'Create Support Ticket' }}
        </h2>
    </x-slot>

    <div class="flex min-h-screen bg-gray-50 gap-x-8">
        <!-- Main Form or Display -->
        <main class="flex-1 flex px-10 py-8">
            <div class="w-full max-w-3xl p-6 bg-white rounded-lg shadow" style="margin-left:10px;">
                @if (!isset($ticket))
                    <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        {{-- Your original form fields go here --}}
                        {{-- Title, Description, Assets, Hidden Inputs --}}
                        <button type="submit"
                            class="w-full bg-blue-700 text-white font-semibold text-xl py-6 rounded-lg shadow text-center hover:bg-blue-800 transition">
                            Create Ticket
                        </button>
                    </form>
                @else
                    {{-- Display Mode --}}
                    <div class="mb-3">
                        <label class="block text-lg font-semibold mb-1">Title</label>
                        <div class="border rounded px-3 py-2 bg-gray-100 text-gray-800">{{ $ticket->title }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="block text-lg font-semibold mb-1">Description</label>
                        <div class="border rounded px-3 py-2 bg-gray-100 text-gray-800">{{ $ticket->description }}</div>
                    </div>

                    <div class="mb-3">
                        <label class="block text-lg font-semibold mb-1">Assets</label>
                        <div class="flex flex-wrap gap-2">
                            @foreach(json_decode($ticket->assets, true) ?? [] as $asset)
                                <div class="border rounded p-2 bg-gray-100">
                                    @if(Str::endsWith($asset, ['.jpg', '.jpeg', '.png']))
                                        <img src="{{ asset('storage/' . $asset) }}" class="h-16 w-16 object-contain" alt="Asset">
                                    @else
                                        <span class="text-sm">📄 {{ basename($asset) }}</span>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="mt-6 flex gap-4">
                        <a href="{{ route('tickets.edit', $ticket->id) }}"
                           class="bg-blue-700 text-white font-semibold px-4 py-2 rounded hover:bg-blue-800 transition">
                            Edit Ticket
                        </a>

                        <form action="{{ route('tickets.destroy', $ticket->id) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this ticket?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="bg-red-600 text-white font-semibold px-4 py-2 rounded hover:bg-red-700 transition">
                                Delete Ticket
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </main>

        <!-- Side Panel -->
        <aside class="w-80 bg-white border-r px-6 py-8">
            <h2 class="text-lg font-semibold mb-6">Ticket Settings</h2>

            @php
                $status = $ticket->status ?? old('status');
                $priority = $ticket->priority ?? old('priority');
                $assignee = $ticket->assignee ?? old('assignee');
                $tshirt = $ticket->tshirt_size ?? old('tshirt_size');
                $stakeholders = json_decode($ticket->stakeholders ?? '[]', true);
            @endphp

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Status</label>
                <div class="text-gray-800">{{ $status }}</div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Priority</label>
                <div class="text-gray-800">{{ $priority }}</div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Assignee</label>
                <div class="text-gray-800">
                    {{ optional(\App\Models\User::find($assignee))->name ?? 'Unassigned' }}
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Stakeholders</label>
                <ul class="list-disc ml-4 text-gray-800">
                    @foreach($stakeholders as $id)
                        <li>{{ \App\Models\User::find($id)->name ?? 'Unknown' }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">T-Shirt Size</label>
                <div class="text-gray-800">{{ $tshirt }}</div>
            </div>
        </aside>
    </div>
</x-app-layout>