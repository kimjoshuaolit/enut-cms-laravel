<div x-data="{
    isOpen: false,
    itemId: null,
    formData: {
        ann_title: '',
        ann_category: '',
        ann_date: '',
        ann_article: '',
        current_ann_media: '',
    },

    async openEdit(id) {
        try {
            const response = await fetch(`/announcement/${id}/edit`);
            const data = await response.json();

            this.itemId = id;
            this.formData.ann_title = data.ann_title || '';
            this.formData.ann_category = data.ann_category || '';
            this.formData.ann_date = data.ann_date || '';
            this.formData.ann_article = data.ann_article || '';
            this.formData.current_ann_media = data.ann_media || '';
            this.isOpen = true;
        } catch (error) {
            console.error('Error loading announcement:', error);
            alert('Failed to load announcement data');
        }
    },

    closeModal() {
        this.isOpen = false;
        this.itemId = null;
    }
}" @open-announcement-edit.window="openEdit($event.detail.id)">

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

                <form :action="`/announcement/${itemId}`" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Header --}}
                    <div class="border-b border-gray-200 bg-white px-6 py-4 dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit Announcement</h3>
                            <button @click="closeModal()" type="button"
                                class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="max-h-[70vh] overflow-y-auto bg-white px-6 py-4 dark:bg-gray-800">
                        <div class="space-y-4">

                            {{-- Title --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Title <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="ann_title" x-model="formData.ann_title" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                                    placeholder="Enter announcement title">
                            </div>

                            {{-- Category & Date --}}
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Category <span class="text-red-500">*</span>
                                    </label>
                                    <select name="ann_category" x-model="formData.ann_category" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                                        <option value="">Select category</option>
                                        <option value="News">News</option>
                                        <option value="Event">Event</option>
                                        <option value="Notice">Notice</option>
                                        <option value="Update">Update</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Date <span class="text-red-500">*</span>
                                    </label>
                                    <input type="date" name="ann_date" x-model="formData.ann_date" required
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                                </div>
                            </div>

                            {{-- Article --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Article <span class="text-red-500">*</span>
                                </label>
                                <textarea name="ann_article" x-model="formData.ann_article" rows="5" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                                    placeholder="Enter announcement content"></textarea>
                            </div>

                            {{-- Current Image --}}
                            <div x-show="formData.current_ann_media">
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Current Image
                                </label>
                                <img :src="`{{ config('services.enutv2.base_url') }}/${formData.current_ann_media}`"
                                    class="mt-1 h-32 w-32 object-cover rounded-lg border-2 border-gray-300">
                            </div>

                            {{-- Replace Image --}}
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Replace Image <span class="text-gray-500 text-xs">(Optional)</span>
                                </label>
                                <input type="file" name="ann_media" accept="image/*"
                                    class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                          file:mr-4 file:rounded-md file:border-0 file:bg-lime-50 file:px-4 file:py-2
                                          file:text-sm file:font-medium file:text-lime-700
                                          hover:file:bg-lime-100 dark:file:bg-lime-900/20 dark:file:text-lime-400">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max 5MB. JPG, PNG, GIF. Leave
                                    empty to keep current image.</p>
                            </div>

                        </div>
                    </div>

                    {{-- Footer --}}
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
