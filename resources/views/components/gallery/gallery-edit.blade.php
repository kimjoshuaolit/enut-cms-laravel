<div x-data="{
    isOpen: false,
    itemId: null,
    formData: {
        title: '',
        area: '',
        cat_title: '{{ $category }}',
        cat_year: '',
        page_no: '',
        current_file_path: ''
    },

    async openEdit(id) {
        try {
            const response = await fetch(`/gallery/${id}/edit`);
            const data = await response.json();

            this.itemId = id;
            this.formData.title = data.title || '';
            this.formData.area = data.area || '';
            this.formData.cat_title = data.cat_title || '{{ $category }}';
            this.formData.cat_year = data.cat_year || '';
            this.formData.page_no = data.page_no || '';
            this.formData.current_file_path = data.file_path || '';
            this.isOpen = true;
        } catch (error) {
            console.error('Error loading item:', error);
            alert('Failed to load item data');
        }
    },

    closeModal() {
        this.isOpen = false;
        this.itemId = null;
    }
}" @open-gallery-edit.window="openEdit($event.detail.id)">

    {{-- Modal --}}
    <div x-show="isOpen" x-cloak @keydown.escape.window="closeModal()" class="fixed inset-0 z-50 overflow-y-auto">

        <div class="flex min-h-screen items-center justify-center p-4">
            {{-- Backdrop --}}
            <div @click="closeModal()" x-show="isOpen" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity">
            </div>

            {{-- Modal Panel --}}
            <div x-show="isOpen" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" @click.stop
                class="relative z-10 w-full max-w-2xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all dark:bg-gray-800">

                <form :action="`/gallery/${itemId}`" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Modal Header --}}
                    <div class="border-b border-gray-200 bg-white px-6 py-4 dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Edit Infographic Image
                            </h3>
                            <button @click="closeModal()" type="button"
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

                            {{-- Page Number (Read-only) --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Page Number
                                </label>
                                <p class="mt-1 text-lg font-semibold text-blue-600 dark:text-blue-400"
                                    x-text="formData.page_no"></p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Use the reorder feature in the table
                                    to change page numbers</p>
                            </div>

                            {{-- Title Dropdown --}}
                            <div>
                                <label for="edit_title"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Target Group <span class="text-red-500">*</span>
                                </label>
                                <select name="title" id="edit_title" x-model="formData.title" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                                    <option value="">-- Select Target Group --</option>
                                    @foreach (\App\Models\Gallery::TITLES as $titleOption)
                                        <option value="{{ $titleOption }}">{{ $titleOption }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Province Dropdown --}}
                            <div>
                                <label for="edit_area"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Province <span class="text-red-500">*</span>
                                </label>
                                <select name="area" id="edit_area" x-model="formData.area" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                                    <option value="">-- Select Province --</option>
                                    @foreach (\App\Models\Gallery::PROVINCES as $province)
                                        <option value="{{ $province }}">{{ $province }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Category Title & Year --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="edit_cat_title"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Category Title <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="cat_title" id="edit_cat_title"
                                        x-model="formData.cat_title" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                                        placeholder="e.g., Infographics">
                                </div>

                                <div>
                                    <label for="edit_cat_year"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Year <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="cat_year" id="edit_cat_year" x-model="formData.cat_year"
                                        required min="1900" max="2100"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                                        placeholder="2024">
                                </div>
                            </div>

                            {{-- Current Image --}}
                            <div x-show="formData.current_file_path && formData.current_file_path !== 'NA'">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Current Image
                                </label>
                                <div
                                    class="mt-2 border-2 border-gray-300 dark:border-gray-600 rounded-lg p-2 inline-block">
                                    <img :src="`{{ url('/') }}/enutV2/${formData.current_file_path.replace('storage/', 'storage/app/public/')}`"
                                        class="h-48 w-auto object-contain rounded">
                                </div>
                            </div>

                            {{-- New Image Upload --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Change Image <span class="text-gray-500 text-xs">(Optional)</span>
                                </label>
                                <input type="file" name="file_path" id="edit_file_path" accept="image/*"
                                    class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                              file:mr-4 file:rounded-md file:border-0 file:bg-lime-50 file:px-4 file:py-2
                                              file:text-sm file:font-medium file:text-lime-700
                                              hover:file:bg-lime-100 dark:file:bg-lime-900/20 dark:file:text-lime-400">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max 5MB. JPG, PNG, GIF. Leave
                                    empty to keep current image.</p>
                            </div>

                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="bg-gray-50 px-6 py-4 dark:bg-gray-900 sm:flex sm:flex-row-reverse sm:gap-3">
                        <button type="submit"
                            class="inline-flex w-full justify-center rounded-md bg-lime-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 sm:w-auto">
                            Update
                        </button>
                        <button @click="closeModal()" type="button"
                            class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
