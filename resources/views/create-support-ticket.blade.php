{{-- resources/views/create-support-ticket.blade.php --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Support Ticket') }}
        </h2>
    </x-slot>
    <div class="flex min-h-screen bg-gray-50 gap-x-8">
        <!-- Main Form -->
        <main class="flex-1 flex px-10 py-8">
            <div class="w-full max-w-3xl p-6 bg-white rounded-lg shadow" style="margin-left:10px;">
                <form action="{{ route('tickets.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-lg font-semibold mb-1">Title</label>
                        <input type="text" name="title" class="w-full border rounded px-3 py-2" required>
                    </div>
                    <div class="mb-3">
                        <label class="block text-lg font-semibold mb-1">Description</label>
                        <textarea name="description" rows="6" class="w-full border rounded px-3 py-2" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="block text-lg font-semibold mb-1">Upload Assets</label>
                        <input 
                            type="file" 
                            name="assets[]" 
                            multiple 
                            accept=".pdf,.doc,.docx,image/jpeg,image/png,image/svg+xml"
                            class="w-full border rounded px-3 py-2"
                            id="assets-input"
                            onchange="handleFilePreview(this)"
                        >
                        <small class="text-gray-500">Max 5 files. Allowed: PDF, Word, JPG, PNG, SVG</small>
                        <div id="assets-preview" class="mt-2 flex flex-wrap gap-2"></div>
                    </div>
                    <!-- Hidden fields for side panel values -->
                    <input type="hidden" name="priority" id="priority-hidden">
                    <input type="hidden" name="stakeholders" id="stakeholders-hidden">
                    <input type="hidden" name="tshirt_size" id="tshirt-size-hidden">
                    <input type="hidden" name="assignee" id="assignee-hidden">
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
                <label class="block text-sm font-medium mb-1">Priority</label>
                <select name="priority" class="w-full border rounded px-2 py-1">
                    <option>Low</option>
                    <option selected>Medium</option>
                    <option>High</option>
                    <option>Critical</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Stakeholders</label>
                <input type="text" name="stakeholders" class="w-full border rounded px-2 py-1" placeholder="Add stakeholders">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">T-Shirt Size</label>
                <select name="tshirt_size" class="w-full border rounded px-2 py-1">
                    <option>XS</option>
                    <option>S</option>
                    <option>M</option>
                    <option>L</option>
                    <option>XL</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Assignee</label>
                <select name="assignee" class="w-full border rounded px-2 py-1">
                    @foreach(\App\Models\User::all() as $user)
                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
        </aside>
    </div>
    <script>
        // Sync side panel values to hidden fields before submit
        document.querySelector('form').addEventListener('submit', function(e) {
            document.getElementById('priority-hidden').value = document.querySelector('aside select[name="priority"]').value;
            document.getElementById('stakeholders-hidden').value = document.querySelector('aside input[name="stakeholders"]').value;
            document.getElementById('tshirt-size-hidden').value = document.querySelector('aside select[name="tshirt_size"]').value;
            document.getElementById('assignee-hidden').value = document.querySelector('aside select[name="assignee"]').value;
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