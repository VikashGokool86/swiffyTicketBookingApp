{{-- resources/views/create-support-ticket.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Support Ticket') }}
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
    <div x-data="ticketFormManager()" x-init="initExistingAssets(@json($assetsArray))" class="flex min-h-screen">
        <!-- Main Form -->
        <main class="flex-1 flex justify-center items-start px-10 py-8">

            <div class="w-full max-w-3xl p-6 bg-white rounded-lg shadow" style="margin-left:10px;">
                <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-lg font-semibold mb-1">Title</label>
                        <input type="text" name="title" class="w-full border rounded px-3 py-2" required>
                        @error('title')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="block text-lg font-semibold mb-1">Description</label>
                        <textarea name="description" rows="6" class="w-full border rounded px-3 py-2" required></textarea>
                        @error('description')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div
                        x-data="assetManager()"
                        x-init="initExistingAssets(@json($assetsArray))"
                        class="space-y-4">
                        <!-- File input -->
                        <label class="block text-lg font-semibold mb-1">Upload Assets</label>
                        <input
                            type="file"
                            id="assets"
                            name="assets[]"
                            multiple
                            accept=".jpg,.jpeg,.png,.pdf,.docx"
                            @change="handleNewFiles($event)"
                            class="w-full border rounded px-3 py-2">
                        <small class="text-gray-500">Max 5 files. Allowed: PDF, Word, JPG, PNG</small>

                        <!-- Preview -->
                        <div class="flex flex-wrap gap-2 mt-2">
                            <template x-for="(item, index) in allAssets" :key="item.key">
                                <div class="relative border rounded p-2 bg-gray-100 inline-block">
                                    <button type="button"
                                        class="absolute top-0 right-0 bg-white text-xs px-1 py-0.5 rounded-full shadow"
                                        @click="item.type === 'existing' ? removeExistingAsset(index) : removeNewFile(index)">
                                        ❌
                                    </button>

                                    <template x-if="item.preview">
                                        <img :src="item.preview" class="h-16 w-16 object-contain">
                                    </template>

                                    <template x-if="!item.preview">
                                        <span class="text-sm">📄 <span x-text="item.name"></span></span>
                                    </template>
                                </div>
                            </template>
                        </div>


                    </div>
                    <!-- Hidden fields for side panel values -->
                    <input type="hidden" name="status" :value="status">
                    <input type="hidden" name="priority" :value="priority">
                    <input type="hidden" name="assignee" :value="assignee">
                    <input type="hidden" name="tshirt_size" :value="tshirtSize">
                    <input type="hidden" name="existing_assets" :value="existingAssets.join(',')">
                    <template x-for="user in stakeholders" :key="user.id">
                        <input type="hidden" name="stakeholders[]" :value="user.id">
                    </template>
                    <div class="mt-6">
                        <button type="submit"
                            class="w-full bg-blue-700 text-white font-semibold text-xl py-6 rounded-lg shadow text-center hover:bg-blue-800 transition">
                            Create Ticket
                        </button>
                    </div>
                </form>
            </div>
        </main>
        <!-- Side Panel -->
        <aside class="w-80 bg-white border-r px-6 py-8">
            <h2 class="text-lg font-semibold mb-6">Ticket Settings</h2>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Status</label>
                <select x-model="status" id="status-select" class="w-full border rounded px-2 py-1" require>
                    <option value="">-- Select a status --</option>
                    <option value="O">Opened</option>
                    <option value="P">In Progress</option>
                    <option value="B">Blocked</option>
                    <option value="C">Closed</option>
                </select>
                @error('status')
                <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
                @enderror

            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Priority</label>
                <select x-model="priority" id="priority-select" class="w-full border rounded px-2 py-1">
                    <option value="">-- Select a priority --</option>
                    <option value="L">Low</option>
                    <option value="M">Medium</option>
                    <option value="H">High</option>
                    <option value="C">Critical</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Assignee</label>
                <select x-model="assignee" id="assignee-select" class="w-full border rounded px-2 py-1">
                    <option value="">-- Select a stakeholder --</option>
                    @foreach(\App\Models\User::all() as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">T-Shirt Size</label>
                <select x-model="tshirtSize" id="tshirt-size-select" class="w-full border rounded px-2 py-1">
                    <option value="">-- Select a tshirt size --</option>
                    <option value="XS">XS</option>
                    <option value="S">S</option>
                    <option value="M">M</option>
                    <option value="L">L</option>
                    <option value="XL">XL</option>
                </select>
            </div>
            <div class="mb-4" x-data="stakeholderManager()">
                <label class="block text-sm font-medium mb-1">Stakeholders</label>

                <!-- Dropdown to select stakeholder -->
                <select class="w-full border rounded px-2 py-1" @change="addStakeholder($event)">
                    <option value="">-- Select a stakeholder --</option>
                    @foreach(\App\Models\User::all() as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>

                <!-- Selected stakeholders list -->
                <div class="mt-3 space-y-1">
                    <template x-for="(user, index) in stakeholders" :key="user.id">
                        <div class="flex items-center justify-between bg-gray-100 px-2 py-1 rounded">
                            <span x-text="user.name"></span>
                            <button type="button" class="text-red-500 hover:text-red-700" @click="removeStakeholder(index)">Remove</button>
                        </div>
                    </template>
                </div>

                <!-- Hidden inputs for form submission -->
                <template x-for="user in stakeholders" :key="'hidden-' + user.id">
                    <input type="hidden" name="stakeholders[]" :value="user.id">
                </template>
        </aside>
    </div>
    </div>

    <script>
        function ticketFormManager() {
            return {
                // Form state
                status: '',
                priority: '',
                assignee: '',
                tshirtSize: '',
                stakeholders: [],
                addedStakeholders: new Set(),

                // Asset state
                existingAssets: [],
                selectedFiles: [],
                previewFiles: [],
                allAssets: [],

                // Init existing assets from controller
                initExistingAssets(array) {
                    this.existingAssets = Array.isArray(array) ? array : [];
                    this.syncAllAssets();
                },

                // File helpers
                isImage(path) {
                    return /\.(jpg|jpeg|png|webp|gif)$/i.test(path);
                },

                handleNewFiles(event) {
                    const input = event.target;
                    const newFiles = Array.from(input.files);

                    const combined = [...this.selectedFiles, ...newFiles];
                    if (combined.length > 5) {
                        alert(`You can only upload up to 5 files. You've already selected ${this.selectedFiles.length}.`);
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

                // Stakeholder logic
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
</x-app-layout>