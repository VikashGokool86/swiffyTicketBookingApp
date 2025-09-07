<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Show Support Ticket') }}
        </h2>
    </x-slot>
    <div class="space-y-4">
        <!-- Validation Errors -->
        @if ($errors->any())
        <div class="text-center">
            <div class="bg-red-50 border border-red-300 text-red-800 px-4 py-3 rounded-md shadow-sm">
                <div class="font-semibold mb-2">Whoops! Something went wrong.</div>
                <ul class="list-disc list-inside space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        @endif
        <!-- Success Message -->
        @if (session('success'))
        <div class="text-center">
            <div class="bg-green-50 border border-green-300 text-green-800 px-4 py-3 rounded-md shadow-sm">
                <div class="font-semibold">{{ session('success') }}</div>
            </div>
        </div>
        @endif
    </div>
    <div x-data="ticketFormManager()" x-init="initTicketData(window.ticketData)" class="flex min-h-screen bg-gray-50 gap-x-8">
        <!-- Main Form -->
        <main class="flex-1 flex px-10 py-8">
            <div class="w-full max-w-3xl p-6 bg-white rounded-lg shadow" style="margin-left:10px;">
                <form action="{{ route('tickets.update', $ticket->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Title -->
                    <div class="mb-3">
                        <label class="block text-lg font-semibold mb-1">Title</label>
                        <input type="text" name="title" class="w-full border rounded px-3 py-2"
                            value="{{ old('title', $ticket->title) }}" required>
                        @error('title')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="block text-lg font-semibold mb-1">Description</label>
                        <textarea name="description" rows="6" class="w-full border rounded px-3 py-2" required>{{ old('description', $ticket->description) }}</textarea>
                        @error('description')
                        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    <script>
                        window.existingAssets = @json(json_decode($ticket->assets, true));
                    </script>
                    <!-- Assets -->
                    <div x-data="assetManager()" x-init="initExistingAssets(window.existingAssets)" class="space-y-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Upload Assets</label>
                        <input type="file" id="assets" name="assets[]" multiple
                            accept=".jpg,.jpeg,.png,.pdf,.docx"
                            @change="handleNewFiles($event)"
                            class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Max 5 files. Allowed: PDF, Word, JPG, PNG</p>

                        <div class="mt-2 flex flex-wrap gap-2">
                            <template x-for="(item, index) in allAssets" :key="item.key">
                                <div class="relative border rounded p-2 bg-gray-100 inline-block">
                                    <button type="button"
                                        class="absolute top-0 right-0 bg-white text-xs px-1 py-0.5 rounded-full shadow hover:bg-red-100"
                                        @click="item.type === 'existing' ? removeExistingAsset(index) : removeNewFile(index)">
                                        ❌
                                    </button>

                                    <template x-if="item.preview">
                                        <img :src="item.preview" class="h-16 w-16 object-contain" alt="">
                                    </template>

                                    <template x-if="!item.preview">
                                        <span class="text-sm">📄 <span x-text="item.name"></span></span>
                                    </template>
                                </div>
                            </template>
                        </div>

                        <input type="hidden" name="existing_assets" :value="existingAssets.join(',')">
                    </div>

                    <!-- Hidden fields synced from side panel -->
                    <input type="hidden" name="status" id="status-hidden" :value="status">
                    <input type="hidden" name="priority" id="priority-hidden" :value="priority">
                    <input type="hidden" name="tshirt_size" id="tshirt-size-hidden" :value="tshirtSize">
                    <input type="hidden" name="assignee" id="assignee-hidden" :value="assignee">

                    <template x-for="user in stakeholders" :key="'hidden-' + user.id">
                        <input type="hidden" name="stakeholders[]" :value="user.id">
                    </template>

                    <!-- Submit -->
                    <div class="mt-6 flex gap-4">
                        <button type="submit"
                            class="bg-blue-700 text-white font-semibold px-4 py-2 rounded hover:bg-blue-800 transition">
                            Update Ticket
                        </button>
                        <button type="button"
                            onclick="document.getElementById('delete-form').submit()"
                            class="bg-red-600 text-white font-semibold px-4 py-2 rounded hover:bg-red-700 transition">
                            Delete Ticket
                        </button>

                    </div>
                </form>

                <!-- Separate Delete Form -->
                <div id="delete-form-container" class="hidden">
                    <form id="delete-form" action="{{ route('tickets.destroy', $ticket->id) }}" method="POST"
                        onsubmit="return confirm('Are you sure you want to delete this ticket?')">
                        @csrf
                        @method('DELETE')
                    </form>
                </div>

            </div>
        </main>

        <!-- Side Panel -->
        <aside class="w-80 bg-white border-r px-6 py-8">
            <h2 class="text-lg font-semibold mb-6">Ticket Settings</h2>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Status</label>
                <select x-model="status" id="status-select" class="w-full border rounded px-2 py-1" required>
                    <option value="">-- Select a status --</option>
                    <option value="O" {{ $ticket->status === 'O' ? 'selected' : '' }}>Opened</option>
                    <option value="P" {{ $ticket->status === 'P' ? 'selected' : '' }}>In Progress</option>
                    <option value="B" {{ $ticket->status === 'B' ? 'selected' : '' }}>Blocked</option>
                    <option value="C" {{ $ticket->status === 'C' ? 'selected' : '' }}>Closed</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Priority</label>
                <select x-model="priority" id="priority-select" class="w-full border rounded px-2 py-1">
                    <option value="">-- Select a priority --</option>
                    <option value="L" {{ $ticket->priority === 'L' ? 'selected' : '' }}>Low</option>
                    <option value="M" {{ $ticket->priority === 'M' ? 'selected' : '' }}>Medium</option>
                    <option value="H" {{ $ticket->priority === 'H' ? 'selected' : '' }}>High</option>
                    <option value="C" {{ $ticket->priority === 'C' ? 'selected' : '' }}>Critical</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Assignee</label>
                <select x-model="assignee" id="assignee-select" class="w-full border rounded px-2 py-1">
                    <option value="">-- Select an assignee --</option>
                    @foreach(\App\Models\User::all() as $user)
                    <option value="{{ $user->id }}" {{ $ticket->assignee == $user->id ? 'selected' : '' }}>
                        {{ $user->name }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">T-Shirt Size</label>
                <select x-model="tshirtSize" id="tshirt-size-select" class="w-full border rounded px-2 py-1">
                    <option value="">-- Select a tshirt size --</option>
                    <option value="XS" {{ $ticket->tshirt_size === 'XS' ? 'selected' : '' }}>XS</option>
                    <option value="S" {{ $ticket->tshirt_size === 'S' ? 'selected' : '' }}>S</option>
                    <option value="M" {{ $ticket->tshirt_size === 'M' ? 'selected' : '' }}>M</option>
                    <option value="L" {{ $ticket->tshirt_size === 'L' ? 'selected' : '' }}>L</option>
                    <option value="XL" {{ $ticket->tshirt_size === 'XL' ? 'selected' : '' }}>XL</option>
                    <option value="XXL" {{ $ticket->tshirt_size === 'XXL' ? 'selected' : '' }}>XXL</option>
                </select>
            </div>
            <div class="mb-4">
                <div class="mt-4 w-full" x-data="stakeholderManager()" x-init="initStakeholders(window.ticketStakeholders)">
                    <label class="block text-sm font-medium mb-1">Stakeholders</label>
                    <select @change="addStakeholder($event)" class="w-full border rounded px-2 py-1">
                        <option value="">-- Select a stakeholder --</option>
                        @foreach(\App\Models\User::all() as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>

                    <div class="mt-3 space-y-2">
                        <template x-for="(user, index) in stakeholders" :key="user.id">
                            <div class="flex items-center justify-between bg-gray-100 px-3 py-2 rounded">
                                <span x-text="user.name"></span>
                                <button type="button" class="text-red-500 hover:text-red-700 text-sm" @click="removeStakeholder(index)">Remove</button>
                            </div>
                        </template>
                    </div>
                </div>


        </aside>
    </div>
    <script>
        function assetManager() {
            return {
                existingAssets: [],
                selectedFiles: [],
                previewFiles: [],
                allAssets: [],
                initExistingAssets(array) {
                    this.existingAssets = Array.isArray(array) ? array : [];
                    this.syncAllAssets();
                },

                isImage(path) {
                    return /\.(jpg|jpeg|png|webp|gif)$/i.test(path);
                },

                syncAllAssets() {
                    const existing = this.existingAssets.map((path, i) => ({
                        key: `existing-${i}`,
                        type: 'existing',
                        name: path.split('/').pop(),
                        preview: this.isImage(path) ? `/storage/${path}` : null
                    }));

                    const uploads = this.previewFiles.map((file, i) => ({
                        key: `new-${i}`,
                        type: 'new',
                        name: file.name,
                        preview: file.preview
                    }));

                    this.allAssets = [...existing, ...uploads];
                },

                handleNewFiles(event) {
                    const input = event.target;
                    const newFiles = Array.from(input.files);
                    const combined = [...this.selectedFiles, ...newFiles];

                    if (combined.length + this.existingAssets.length > 5) {
                        alert(`Max 5 files allowed.`);
                        input.value = '';
                        return;
                    }

                    this.selectedFiles = combined;
                    this.previewFiles = this.selectedFiles.map(file => ({
                        file,
                        name: file.name,
                        preview: file.type.startsWith('image/') ? URL.createObjectURL(file) : null
                    }));

                    const dataTransfer = new DataTransfer();
                    this.selectedFiles.forEach(file => dataTransfer.items.add(file));
                    input.files = dataTransfer.files;

                    this.syncAllAssets();
                },

                removeNewFile(index) {
                    const removed = this.previewFiles.splice(index, 1)[0];
                    if (removed.preview) URL.revokeObjectURL(removed.preview);
                    this.selectedFiles.splice(index, 1);

                    const dataTransfer = new DataTransfer();
                    this.selectedFiles.forEach(file => dataTransfer.items.add(file));
                    document.getElementById('assets').files = dataTransfer.files;

                    this.syncAllAssets();
                },

                removeExistingAsset(index) {
                    this.existingAssets.splice(index, 1);
                    this.syncAllAssets();
                }
            };
        }
    </script>
    <script>
        function ticketFormManager() {
            return {
                status: '',
                priority: '',
                assignee: '',
                tshirtSize: '',
                stakeholders: [],
                addedStakeholders: new Set(),
                existingAssets: [],
                selectedFiles: [],
                previewFiles: [],
                allAssets: [],

                initTicketData(data) {
                    this.status = data.status;
                    this.priority = data.priority;
                    this.assignee = data.assignee;
                    this.tshirtSize = data.tshirtSize;
                    this.stakeholders = data.stakeholders;
                    this.addedStakeholders = new Set(data.stakeholderIds);
                    this.initExistingAssets(data.assets);
                },

                initExistingAssets(array) {
                    this.existingAssets = Array.isArray(array) ? array : [];
                    this.syncAllAssets();
                },

                syncAllAssets() {
                    const existing = this.existingAssets.map((path, i) => ({
                        key: `existing-${i}`,
                        type: 'existing',
                        name: path.split('/').pop(),
                        preview: /\.(jpg|jpeg|png|webp|gif)$/i.test(path) ? `/storage/${path}` : null
                    }));

                    const uploads = this.previewFiles.map((file, i) => ({
                        key: `new-${i}`,
                        type: 'new',
                        name: file.name,
                        preview: file.preview
                    }));

                    this.allAssets = [...existing, ...uploads];
                },

                handleNewFiles(event) {
                    const input = event.target;
                    const newFiles = Array.from(input.files);
                    const combined = [...this.selectedFiles, ...newFiles];

                    if (combined.length + this.existingAssets.length > 5) {
                        alert(`Max 5 files allowed.`);
                        input.value = '';
                        return;
                    }

                    this.selectedFiles = combined;
                    this.previewFiles = this.selectedFiles.map(file => ({
                        file,
                        name: file.name,
                        preview: file.type.startsWith('image/') ? URL.createObjectURL(file) : null
                    }));

                    const dataTransfer = new DataTransfer();
                    this.selectedFiles.forEach(file => dataTransfer.items.add(file));
                    input.files = dataTransfer.files;

                    this.syncAllAssets();
                },

                removeNewFile(index) {
                    const removed = this.previewFiles.splice(index, 1)[0];
                    if (removed.preview) URL.revokeObjectURL(removed.preview);
                    this.selectedFiles.splice(index, 1);

                    const dataTransfer = new DataTransfer();
                    this.selectedFiles.forEach(file => dataTransfer.items.add(file));
                    document.getElementById('assets').files = dataTransfer.files;

                    this.syncAllAssets();
                },

                removeExistingAsset(index) {
                    this.existingAssets.splice(index, 1);
                    this.syncAllAssets();
                },

                addStakeholder(event) {
                    const userId = event.target.value;
                    const userName = event.target.options[event.target.selectedIndex].text;

                    if (!userId || this.addedStakeholders.has(userId)) return;

                    this.addedStakeholders.add(userId);
                    this.stakeholders.push({
                        id: userId,
                        name: userName
                    });
                    event.target.value = '';
                },

                removeStakeholder(index) {
                    const removed = this.stakeholders.splice(index, 1)[0];
                    this.addedStakeholders.delete(removed.id);
                }
            };
        }
    </script>
    <script>
        window.ticketData = {
            status: @json(old('status', $ticket->status)),
            priority: @json(old('priority', $ticket->priority)),
            assignee: @json(old('assignee', $ticket->assignee)),
            tshirtSize: @json(old('tshirt_size', $ticket->tshirt_size)),
            stakeholders: @json($preselectedStakeholders),
            stakeholderIds: @json($stakeholderIds),
            assets: @json(json_decode($ticket->assets ?? '[]'))
        };
    </script>
    <script>
        window.ticketStakeholders = @json(\App\Models\User::whereIn('id', json_decode($ticket->stakeholders ?? '[]'))->get(['id', 'name']));
    </script>

</x-app-layout>