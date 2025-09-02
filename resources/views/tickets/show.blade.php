<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Show Support Ticket') }}
        </h2>
    </x-slot>

    <div class="flex min-h-screen bg-gray-50 gap-x-8">
        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops! Something went wrong.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="mb-4 text-green-600 font-semibold">
                {{ session('success') }}
            </div>
        @endif

        <!-- Main Form -->
        <main class="flex-1 flex px-10 py-8">
            <div class="w-full max-w-3xl p-6 bg-white rounded-lg shadow" style="margin-left:10px;">
                <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="block text-lg font-semibold mb-1">Title</label>
                        <input type="text" name="title" class="w-full border rounded px-3 py-2"
                               value="{{ old('title', $ticket->title) }}" required>
                        @error('title')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="block text-lg font-semibold mb-1">Description</label>
                        <textarea name="description" rows="6" class="w-full border rounded px-3 py-2" required>{{ old('description', $ticket->description) }}</textarea>
                        @error('description')
                            <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="block text-lg font-semibold mb-1">Upload New Assets</label>
                        <input type="file" name="assets[]" multiple
                               accept=".pdf,.doc,.docx,image/jpeg,image/png,image/svg+xml"
                               class="w-full border rounded px-3 py-2" id="assets-input" onchange="handleFilePreview(this)">
                        <small class="text-gray-500">Max 5 files. Allowed: PDF, Word, JPG, PNG, SVG</small>
                        <div id="assets-preview" class="mt-2 flex flex-wrap gap-2">
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

                    <!-- Hidden fields for syncing side panel -->
                    <input type="hidden" name="status" id="status-hidden" value="{{ old('status', $ticket->status) }}">
                    <input type="hidden" name="priority" id="priority-hidden" value="{{ old('priority', $ticket->priority) }}">
                    <input type="hidden" name="tshirt_size" id="tshirt-size-hidden" value="{{ old('tshirt_size', $ticket->tshirt_size) }}">
                    <input type="hidden" name="assignee" id="assignee-hidden" value="{{ old('assignee', $ticket->assignee) }}">
                    <input type="hidden" name="stakeholders" id="stakeholders-hidden" value="{{ old('stakeholders', json_encode($ticket->stakeholders)) }}">
                    <div id="stakeholders-hidden-container"></div>

                    <div class="mt-6 flex gap-4">
                        <button type="submit"
                                class="bg-blue-700 text-white font-semibold px-4 py-2 rounded hover:bg-blue-800 transition">
                            Update Ticket
                        </button>

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
                </form>
            </div>
        </main>

        <!-- Side Panel -->
        <aside class="w-80 bg-white border-r px-6 py-8">
            <h2 class="text-lg font-semibold mb-6">Ticket Settings</h2>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Status</label>
                <select id="status-select" class="w-full border rounded px-2 py-1" required>
                    <option value="">-- Select a status --</option>
                    <option value="O" {{ $ticket->status === 'O' ? 'selected' : '' }}>Opened</option>
                    <option value="P" {{ $ticket->status === 'P' ? 'selected' : '' }}>In Progress</option>
                    <option value="B" {{ $ticket->status === 'B' ? 'selected' : '' }}>Blocked</option>
                    <option value="C" {{ $ticket->status === 'C' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Priority</label>
                <select id="priority-select" class="w-full border rounded px-2 py-1">
                    <option value="">-- Select a priority --</option>
                    <option value="L" {{ $ticket->priority === 'L' ? 'selected' : '' }}>Low</option>
                    <option value="M" {{ $ticket->priority === 'M' ? 'selected' : '' }}>Medium</option>
                    <option value="H" {{ $ticket->priority === 'H' ? 'selected' : '' }}>High</option>
                    <option value="C" {{ $ticket->priority === 'C' ? 'selected' : '' }}>Critical</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Assignee</label>
                <select id="assignee-select" class="w-full border rounded px-2 py-1">
                    <option value="">-- Select an assignee --</option>
                    @foreach(\App\Models\User::all() as $user)
                        <option value="{{ $user->id }}" {{ $ticket->assignee == $user->id ? 'selected' : '' }}>
                            {{ $user->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Stakeholders</label>
                <select id="stakeholder-select" class="w-full border rounded px-2 py-1">
                    <option value="">-- Select a stakeholder --</option>
                    @foreach(\App\Models\User::all() as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>

                <div id="selected-stakeholders" class="mt-3 space-y-1">
                    @foreach(json_decode($ticket->stakeholders, true) ?? [] as $id)
                        <div class="flex items-center justify-between bg-gray-100 px-2 py-1 rounded">
                            <span>{{ \App\Models\User::find($id)->name ?? 'Unknown' }}</span>
                            <input type="hidden" name="stakeholders[]" value="{{ $id }}">
                            <button type="button" class="text-red-500 hover:text-red-700 remove-btn">Remove</button>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">T-Shirt Size</label>
                <select id="tshirt-size-select" class="w-full border rounded px-2 py-1">
                    <option value="">-- Select a tshirt size --</option>
                    <option value="XS" {{ $ticket->tshirt_size === 'XS' ? 'selected' : '' }}>XS</option>
                    <option value="S" {{ $ticket->tshirt_size === 'S' ? 'selected' : '' }}>S</option>
                    <option value="M" {{ $ticket->tshirt_size === 'M' ? 'selected' : '' }}>M</option>
                    <option value="L" {{ $ticket->tshirt_size === 'L' ? 'selected' : '' }}>L</option>
                    <option value="XL" {{ $ticket->tshirt_size === 'XL' ? 'selected' : '' }}>XL</option>
                    <option value="XXL" {{ $ticket->tshirt_size === 'XXL' ? 'selected' : '' }}>XXL</option>
                </select>
            </div>
        </aside>
    </div>
     <script>
        // Sync side panel values to hidden fields before submit
        document.querySelector('form').addEventListener('submit', function(e) {
            // Sync scalar values
            document.getElementById('status-hidden').value = statusHidden.value;
            document.getElementById('priority-hidden').value = priorityHidden.value;
            document.getElementById('tshirt-size-hidden').value = tshirtSizeSelect.value;
            document.getElementById('assignee-hidden').value = assigneeSelect.value;

            // Clear previous stakeholder inputs
            const container = document.getElementById('stakeholders-hidden-container');
            container.innerHTML = '';

            // Sync stakeholder array
            const stakeholderIds = Array.from(document.querySelectorAll('#selected-stakeholders input[name="stakeholders[]"]'))
                .map(input => input.value);

            stakeholderIds.forEach(id => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'stakeholders[]';
                input.value = id;
                container.appendChild(input);
            });
        });

        const select = document.getElementById('stakeholder-select');
        const container = document.getElementById('selected-stakeholders');
        const hiddenContainer = document.getElementById('stakeholders-hidden-container');
        const added = new Set();

        select.addEventListener('change', function() {
            const userId = this.value;
            const userName = this.options[this.selectedIndex].text;

            if (!userId || added.has(userId)) return;

            added.add(userId);

            // Visible UI element
            const displayWrapper = document.createElement('div');
            displayWrapper.className = "flex items-center justify-between bg-gray-100 px-2 py-1 rounded";

            displayWrapper.innerHTML = `
                <span>${userName}</span>
                <button type="button" class="text-red-500 hover:text-red-700 remove-btn">Remove</button>
            `;

            container.appendChild(displayWrapper);

            // Hidden input for form submission
            const hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'stakeholders[]';
            hiddenInput.value = userId;

            hiddenContainer.appendChild(hiddenInput);

            // Remove logic
            displayWrapper.querySelector('.remove-btn').addEventListener('click', () => {
                added.delete(userId);
                displayWrapper.remove();
                hiddenInput.remove();
            });

            this.value = ""; // Reset dropdown
        });

        // Live syncing for priority and tshirt_size dropdowns
        const statusSelect = document.getElementById('status-select');
        const prioritySelect = document.getElementById('priority-select');
        const assigneeSelect = document.getElementById('assignee-select');
        const tshirtSizeSelect = document.getElementById('tshirt-size-select');

        const statusHidden = document.getElementById('status-hidden');
        const priorityHidden = document.getElementById('priority-hidden');
        const assigneeHidden = document.getElementById('assignee-hidden');
        const tshirtSizeHidden = document.getElementById('tshirt-size-hidden');

        statusSelect.addEventListener('change', function() {
            statusHidden.value = this.value;
        });

        prioritySelect.addEventListener('change', function() {
            priorityHidden.value = this.value;
        });

        assigneeSelect.addEventListener('change', function() {
            assigneeHidden.value = this.value;
        });

        tshirtSizeSelect.addEventListener('change', function() {
            tshirtSizeHidden.value = this.value;
        });

        function handleFilePreview(input) {
            const preview = document.getElementById('assets-preview');
            preview.innerHTML = '';
            const files = Array.from(input.files).slice(0, 5); // Limit to 5 files

            if (input.files.length > 5) {
                alert('You can only upload up to 5 files.');
                input.value = '';
                return;
            }

            files.forEach(file => {
                const fileType = file.type;
                const fileDiv = document.createElement('div');
                fileDiv.className = "border rounded p-2 bg-gray-100";

                // Image preview
                if (fileType.startsWith('image/')) {
                    const img = document.createElement('img');
                    img.className = "h-16 w-16 object-contain";
                    img.src = URL.createObjectURL(file);
                    img.onload = () => URL.revokeObjectURL(img.src);
                    fileDiv.appendChild(img);
                } else {
                    // File icon and name for non-images
                    const icon = document.createElement('span');
                    icon.className = "inline-block mr-2";
                    icon.textContent = "📄";
                    fileDiv.appendChild(icon);

                    const name = document.createElement('span');
                    name.textContent = file.name;
                    fileDiv.appendChild(name);
                }

                preview.appendChild(fileDiv);
            });
        }
    </script>

</x-app-layout>