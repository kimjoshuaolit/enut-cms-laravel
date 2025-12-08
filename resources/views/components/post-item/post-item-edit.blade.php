<div x-data="{
    isOpen: false,
    itemId: null,
    formData: {
        post_title: '',
        post_description: '',
        post_survey: '',
        post_year: '',
        post_cat: '{{ $category }}',
        current_pic_file: '',
        current_pdf_path: ''
    },

    async openEdit(id) {
        try {
            const response = await fetch(`/post-items/${id}/edit`);
            const data = await response.json();

            this.itemId = id;
            this.formData.post_title = data.post_title || '';
            this.formData.post_description = data.post_description || '';
            this.formData.post_survey = data.post_survey || '';
            this.formData.post_year = data.post_year || '';
            this.formData.current_pic_file = data.pic_file || '';
            this.formData.current_pdf_path = data.pdf_path || '';
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
}" @open-edit.window="openEdit($event.detail.id)">

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

                <form :action="`/post-items/${itemId}`" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Modal Header --}}
                    <div class="border-b border-gray-200 bg-white px-6 py-4 dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                                Edit {{ $category }} Item
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

                            <input type="hidden" name="post_cat" :value="formData.post_cat">

                            {{-- Title --}}
                            <div>
                                <label for="edit_post_title"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="post_title" id="edit_post_title"
                                    x-model="formData.post_title" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                                    placeholder="Enter title">
                            </div>

                            {{-- Description --}}
                            <div>
                                <label for="edit_post_description"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Description
                                </label>
                                <textarea name="post_description" id="edit_post_description" rows="3" x-model="formData.post_description"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                                    placeholder="Enter description"></textarea>
                            </div>

                            {{-- Survey & Year --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label for="edit_post_survey"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Survey
                                    </label>
                                    <input type="text" name="post_survey" id="edit_post_survey"
                                        x-model="formData.post_survey"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                                        placeholder="e.g., NNS 2018">
                                </div>

                                <div>
                                    <label for="edit_post_year"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Year <span class="text-red-500">*</span>
                                    </label>
                                    <input type="number" name="post_year" id="edit_post_year"
                                        x-model="formData.post_year" required min="1900" max="2100"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                                        placeholder="2024">
                                </div>
                            </div>

                            {{-- Current Image --}}
                            <div x-show="formData.current_pic_file && formData.current_pic_file !== 'NA'">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Current Image
                                </label>
                                <img :src="`{{ url('/') }}/enutV2/${formData.current_pic_file.replace('storage/', 'storage/app/public/')}`"
                                    class="mt-1 h-32 w-32 object-cover rounded-lg border-2 border-gray-300">
                            </div>

                            {{-- New Image Upload --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Change Image <span class="text-gray-500 text-xs">(Optional)</span>
                                </label>
                                <input type="file" name="pic_file" id="edit_pic_file" accept="image/*"
                                    class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                              file:mr-4 file:rounded-md file:border-0 file:bg-lime-50 file:px-4 file:py-2
                                              file:text-sm file:font-medium file:text-lime-700
                                              hover:file:bg-lime-100 dark:file:bg-lime-900/20 dark:file:text-lime-400">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max 5MB. JPG, PNG, GIF. Leave
                                    empty to keep current image.</p>
                            </div>

                            {{-- Current PDF --}}
                            <div x-show="formData.current_pdf_path">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Current PDF
                                </label>
                                <a :href="`{{ url('/') }}/enutV2/${formData.current_pdf_path.replace('storage/', 'storage/app/public/')}`"
                                    target="_blank"
                                    class="mt-1 inline-flex items-center gap-2 text-blue-600 hover:text-blue-700">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                    </svg>
                                    View Current PDF
                                </a>
                            </div>

                            {{-- New PDF Upload --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Change PDF <span class="text-gray-500 text-xs">(Optional)</span>
                                </label>
                                <input type="file" name="pdf_path" id="edit_pdf_path" accept=".pdf"
                                    class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                              file:mr-4 file:rounded-md file:border-0 file:bg-lime-50 file:px-4 file:py-2
                                              file:text-sm file:font-medium file:text-lime-700
                                              hover:file:bg-lime-100 dark:file:bg-lime-900/20 dark:file:text-lime-400">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max 10MB. PDF only. Leave
                                    empty to keep current PDF.</p>
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
