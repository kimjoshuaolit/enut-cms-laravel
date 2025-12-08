<div x-data="{
    isOpen: {{ $errors->any() ? 'true' : 'false' }},
    previewImages: [],
    draggingIndex: null,

    handleFiles(event) {
        const files = Array.from(event.target.files);

        // Debug log
        console.log('Files selected:', files.length);

        this.previewImages = files.map((file, index) => {
            const url = URL.createObjectURL(file);
            console.log('Created preview URL:', url); // Debug log
            return {
                file: file,
                url: url,
                order: index + 1
            };
        });

        console.log('Preview images:', this.previewImages); // Debug log
    },

    dragStart(index) {
        this.draggingIndex = index;
    },

    dragOver(event) {
        event.preventDefault();
    },

    drop(dropIndex) {
        if (this.draggingIndex === null) return;

        const draggedItem = this.previewImages[this.draggingIndex];
        this.previewImages.splice(this.draggingIndex, 1);
        this.previewImages.splice(dropIndex, 0, draggedItem);

        // Update order numbers
        this.previewImages.forEach((img, idx) => {
            img.order = idx + 1;
        });

        this.draggingIndex = null;
        this.updateFileInput();
    },

    removeImage(index) {
        this.previewImages.splice(index, 1);
        this.previewImages.forEach((img, idx) => {
            img.order = idx + 1;
        });
        this.updateFileInput();
    },

    updateFileInput() {
        // Create new DataTransfer to update file input with reordered files
        const dt = new DataTransfer();
        this.previewImages.forEach(img => {
            dt.items.add(img.file);
        });
        document.getElementById('images').files = dt.files;
    }
}">
    {{-- Success/Error Messages --}}
    @if (session()->has('success'))
        <div
            class="mb-4 rounded-lg border border-green-600 bg-green-50 p-4 text-sm text-green-800 dark:bg-green-900/20 dark:text-green-400">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.483 4.79-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l4-5.5Z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('success') }}
            </div>
        </div>
    @endif

    @if (session()->has('error'))
        <div
            class="mb-4 rounded-lg border border-red-600 bg-red-50 p-4 text-sm text-red-800 dark:bg-red-900/20 dark:text-red-400">
            <div class="flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="h-5 w-5">
                    <path fill-rule="evenodd"
                        d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z"
                        clip-rule="evenodd" />
                </svg>
                {{ session('error') }}
            </div>
        </div>
    @endif

    {{-- Add Button --}}
    <button @click="isOpen = true" type="button"
        class="inline-flex items-center gap-2 rounded-lg bg-lime-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 dark:bg-lime-600 dark:hover:bg-lime-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Upload Images
    </button>

    {{-- Modal --}}
    <div x-show="isOpen" x-cloak @keydown.escape.window="isOpen = false" class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">

        <div class="flex min-h-screen items-center justify-center p-4">
            {{-- Backdrop --}}
            <div @click="isOpen = false" x-show="isOpen" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                aria-hidden="true"></div>

            {{-- Modal Panel --}}
            <div x-show="isOpen" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" @click.stop
                class="relative z-10 w-full max-w-4xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all dark:bg-gray-800">

                <form action="{{ route('gallery.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Modal Header --}}
                    <div class="border-b border-gray-200 bg-white px-6 py-4 dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Upload Infographic Images
                            </h3>
                            <button @click="isOpen = false" type="button"
                                class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Modal Body --}}
                    <div class="max-h-[70vh] overflow-y-auto bg-white px-6 py-4 dark:bg-gray-800">
                        <div class="space-y-4">

                            {{-- Title Dropdown --}}
                            <div>
                                <label for="title"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Target Group <span class="text-red-500">*</span>
                                </label>
                                <select name="title" id="title" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm @error('title') border-red-500 @enderror">
                                    <option value="">-- Select Target Group --</option>
                                    @foreach (\App\Models\Gallery::TITLES as $titleOption)
                                        <option value="{{ $titleOption }}"
                                            {{ old('title') == $titleOption ? 'selected' : '' }}>
                                            {{ $titleOption }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('title')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Province Dropdown --}}
                            <div>
                                <label for="area"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Province <span class="text-red-500">*</span>
                                </label>
                                <select name="area" id="area" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm @error('area') border-red-500 @enderror">
                                    <option value="">-- Select Province --</option>
                                    @foreach (\App\Models\Gallery::PROVINCES as $province)
                                        <option value="{{ $province }}"
                                            {{ old('area') == $province ? 'selected' : '' }}>
                                            {{ $province }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('area')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Category Title & Year --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="cat_title"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Category Title <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="cat_title" id="cat_title"
                                        value="{{ old('cat_title', $category) }}" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm @error('cat_title') border-red-500 @enderror"
                                        placeholder="e.g., Infographics">
                                    @error('cat_title')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="cat_year"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Year <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="cat_year" id="cat_year"
                                        value="{{ old('cat_year', date('Y')) }}" required min="1900"
                                        max="2100"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm @error('cat_year') border-red-500 @enderror"
                                        placeholder="2024">
                                    @error('cat_year')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            {{-- Multiple Image Upload --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Images <span class="text-red-500">*</span> <span
                                        class="text-gray-500 text-xs">(Max 20 images, 5MB each)</span>
                                </label>
                                <input type="file" name="images[]" id="images" accept="image/*" multiple
                                    required @change="handleFiles($event)"
                                    class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                              file:mr-4 file:rounded-md file:border-0 file:bg-lime-50 file:px-4 file:py-2
                                              file:text-sm file:font-medium file:text-lime-700
                                              hover:file:bg-lime-100 dark:file:bg-lime-900/20 dark:file:text-lime-400
                                              @error('images') border-red-500 @enderror
                                              @error('images.*') border-red-500 @enderror">
                                @error('images')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                                @error('images.*')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Draggable Image Previews --}}
                            <div x-show="previewImages.length > 0" class="mt-4">
                                <div class="flex items-center justify-between mb-3">
                                    <p class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Preview (<span x-text="previewImages.length"></span> images) - <span
                                            class="text-blue-600 dark:text-blue-400">Drag to reorder</span>
                                    </p>
                                </div>

                                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 gap-3">
                                    <template x-for="(image, index) in previewImages" :key="index">
                                        <div class="relative group" @dragstart="dragStart(index)"
                                            @dragover="dragOver($event)" @drop="drop(index)" draggable="true">

                                            {{-- Main Image Container --}}
                                            <div
                                                class="relative w-full h-24 bg-gray-200 dark:bg-gray-700 rounded-lg overflow-hidden border-2 border-gray-300 dark:border-gray-600 group-hover:border-blue-500 transition-colors cursor-move">
                                                <img :src="image.url" class="w-full h-full object-cover"
                                                    draggable="false">

                                                {{-- Page Number Badge - High z-index --}}
                                                <div
                                                    class="absolute top-2 left-2 bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded shadow-lg z-20 pointer-events-none">
                                                    Page <span x-text="image.order"></span>
                                                </div>

                                                {{-- Remove Button - Highest z-index and clickable --}}
                                                <button @click.prevent.stop="removeImage(index)" type="button"
                                                    class="absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white rounded-full p-1.5 shadow-lg opacity-0 group-hover:opacity-100 transition-opacity z-30 cursor-pointer">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3"
                                                        viewBox="0 0 20 20" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                </button>

                                                {{-- Drag Indicator - Lowest z-index, non-interactive --}}
                                                <div
                                                    class="absolute inset-0 bg-black opacity-0 group-hover:opacity-20 transition-opacity flex items-center justify-center z-10 pointer-events-none">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-white"
                                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            stroke-width="2"
                                                            d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </div>
                                    </template>
                                </div>

                                <p class="mt-3 text-xs text-center text-gray-500 dark:text-gray-400">
                                    💡 <strong>Tip:</strong> Drag images to reorder. Click X to remove. The order here
                                    will be the page numbers.
                                </p>
                            </div>

                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="bg-gray-50 px-6 py-4 dark:bg-gray-900 sm:flex sm:flex-row-reverse sm:gap-3">
                        <button type="submit" :disabled="previewImages.length === 0"
                            class="inline-flex w-full justify-center rounded-md bg-lime-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed sm:w-auto">
                            Upload &nbsp; <span x-show="previewImages.length > 0"
                                x-text="previewImages.length"></span> &nbsp;
                            Images
                        </button>
                        <button @click="isOpen = false; previewImages = []" type="button"
                            class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
