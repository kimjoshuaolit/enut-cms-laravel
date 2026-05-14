<div x-data="{ isOpen: {{ $errors->any() ? 'true' : 'false' }} }">
    {{-- Success/Error Messages --}}
    @if (session()->has('success'))
        <div class="mb-4 rounded-lg border border-green-600 bg-green-50 p-4 text-sm text-green-800">
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
        <div class="mb-4 rounded-lg border border-red-600 bg-red-50 p-4 text-sm text-red-800">
            {{ session('error') }}
        </div>
    @endif

    {{-- Add Button --}}
    <button @click="isOpen = true" type="button"
        class="inline-flex items-center gap-2 rounded-lg bg-lime-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 dark:bg-lime-600 dark:hover:bg-lime-700">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add Announcement
    </button>

    {{-- Modal --}}
    <div x-show="isOpen" x-cloak @keydown.escape.window="isOpen = false" class="fixed inset-0 z-50 overflow-y-auto"
        aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div @click="isOpen = false" x-show="isOpen" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
                aria-hidden="true"></div>

            <div x-show="isOpen" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" @click.stop
                class="relative z-10 w-full max-w-2xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all dark:bg-gray-800">

                <form action="{{ route('announcement.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Header --}}
                    <div class="border-b border-gray-200 bg-white px-6 py-4 dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add New Announcement</h3>
                            <button @click="isOpen = false" type="button" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="bg-white px-6 py-4 dark:bg-gray-800 space-y-4">

                        {{-- Title --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="ann_title" value="{{ old('ann_title') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm @error('ann_title') border-red-500 @enderror"
                                placeholder="Enter announcement title">
                            @error('ann_title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Category & Date --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Category <span class="text-red-500">*</span>
                                </label>
                                <select name="ann_category" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                                    <option value="">Select category</option>
                                    <option value="News" {{ old('ann_category') == 'News' ? 'selected' : '' }}>News
                                    </option>
                                    <option value="Event" {{ old('ann_category') == 'Event' ? 'selected' : '' }}>Event
                                    </option>
                                    <option value="Notice" {{ old('ann_category') == 'Notice' ? 'selected' : '' }}>
                                        Notice</option>
                                    <option value="Update" {{ old('ann_category') == 'Update' ? 'selected' : '' }}>
                                        Update</option>
                                </select>
                                @error('ann_category')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Date <span class="text-red-500">*</span>
                                </label>
                                <input type="date" name="ann_date" value="{{ old('ann_date', date('Y-m-d')) }}"
                                    required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                                @error('ann_date')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Article --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Article <span class="text-red-500">*</span>
                            </label>
                            <textarea name="ann_article" rows="5" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm @error('ann_article') border-red-500 @enderror"
                                placeholder="Enter announcement content">{{ old('ann_article') }}</textarea>
                            @error('ann_article')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Media --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Image (Optional)
                            </label>
                            <input type="file" name="ann_media" accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-500
                                      file:mr-4 file:rounded-md file:border-0 file:bg-lime-50 file:px-4 file:py-2
                                      file:text-sm file:font-medium file:text-lime-700 hover:file:bg-lime-100">
                            <p class="mt-1 text-xs text-gray-500">Max 5MB. JPG, PNG, GIF</p>
                            @error('ann_media')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="bg-gray-50 px-6 py-4 dark:bg-gray-900 sm:flex sm:flex-row-reverse sm:gap-3">
                        <button type="submit"
                            class="inline-flex w-full justify-center rounded-md bg-lime-600 px-4 py-2 text-sm font-medium text-white hover:bg-lime-700 sm:w-auto">
                            Save
                        </button>
                        <button @click="isOpen = false" type="button"
                            class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
