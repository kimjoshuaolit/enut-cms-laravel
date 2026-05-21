<div x-data="{
    isOpen: {{ $errors->any() ? 'true' : 'false' }},
    surveyOption: '{{ old('survey_option', 'existing') }}',
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
        class="inline-flex items-center gap-2 rounded-lg bg-lime-600 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
            stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Add New PUF Item
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
                aria-hidden="true">
            </div>

            {{-- Modal Panel --}}
            <div x-show="isOpen" x-transition:enter="ease-out duration-300"
                x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" @click.stop
                class="relative z-10 w-full max-w-2xl transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all dark:bg-gray-800">

                <form action="{{ route('puf.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Header --}}
                    <div class="border-b border-gray-200 bg-white px-6 py-4 dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Add New PUF Item</h3>
                            <button @click="isOpen = false" type="button"
                                class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Body --}}
                    <div class="max-h-[70vh] overflow-y-auto bg-white px-6 py-4 dark:bg-gray-800 space-y-4">

                        {{-- Title --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="post_title" value="{{ old('post_title') }}" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm @error('post_title') border-red-500 @enderror"
                                placeholder="Enter title">
                            @error('post_title')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Description --}}
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea name="post_description" rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                                placeholder="Enter description">{{ old('post_description') }}</textarea>
                        </div>

                        {{-- Description 2 --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description
                                2</label>
                            <textarea name="post_description2" rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                                placeholder="Enter additional description">{{ old('post_description2') }}</textarea>
                        </div>

                        {{-- Survey Type & Post Type --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Survey <span class="text-red-500">*</span>
                                </label>
                                <select name="post_survey" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                                    <option value="">Select Survey</option>
                                    @foreach (['Updating', 'ENNS', 'NNS', 'RNAS'] as $survey)
                                        <option value="{{ $survey }}"
                                            {{ old('post_survey') == $survey ? 'selected' : '' }}>
                                            {{ $survey }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('post_survey')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Type <span class="text-red-500">*</span>
                                </label>
                                <select name="post_type" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                                    <option value="">Select Type</option>
                                    @foreach (['biochemical', 'maternal', 'food', 'socio', 'anthrop', 'iycf', 'dietary', 'dietary_indiv', 'socio_dem', 'clinical', 'socio1', 'food1'] as $type)
                                        <option value="{{ $type }}"
                                            {{ old('post_type') == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('post_type')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Year & Date Published --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Year <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="post_year" value="{{ old('post_year', date('Y')) }}"
                                    required min="1900" max="2100"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                                @error('post_year')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Date Published
                                </label>
                                <input type="text" name="date_pub" value="{{ old('date_pub') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                                    placeholder="e.g., 2024">
                            </div>
                        </div>

                        {{-- Survey Category --}}
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Survey Category <span class="text-red-500">*</span>
                            </label>

                            {{-- Toggle --}}
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="survey_option" value="existing"
                                        x-model="surveyOption" class="text-lime-600 focus:ring-lime-500">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Select Existing</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="survey_option" value="new" x-model="surveyOption"
                                        class="text-lime-600 focus:ring-lime-500">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Create New</span>
                                </label>
                            </div>

                            {{-- Existing Survey Dropdown --}}
                            <div x-show="surveyOption === 'existing'">
                                <select name="existing_survey"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                                    <option value="">Select Survey Category</option>
                                    @foreach ($surveys as $survey)
                                        <option value="{{ $survey->id }}"
                                            {{ old('existing_survey') == $survey->id ? 'selected' : '' }}>
                                            {{ $survey->sub_category }} ({{ $survey->value }})
                                        </option>
                                    @endforeach
                                </select>
                                @error('existing_survey')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- New Survey Input --}}
                            <div x-show="surveyOption === 'new'">
                                <input type="text" name="new_survey_name" value="{{ old('new_survey_name') }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                                    placeholder="e.g., 2018-2019 Updating Survey">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">This will be used as the
                                    folder name for PUF files.</p>
                                @error('new_survey_name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- PUF File (ZIP/RAR) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                PUF File (ZIP/RAR)
                            </label>
                            <input type="file" name="puf_file" accept=".zip,.rar"
                                class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                      file:mr-4 file:rounded-md file:border-0 file:bg-lime-50 file:px-4 file:py-2
                                      file:text-sm file:font-medium file:text-lime-700
                                      hover:file:bg-lime-100 dark:file:bg-lime-900/20 dark:file:text-lime-400">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max 100MB. ZIP or RAR only. File
                                will be renamed to the title.</p>
                            @error('puf_file')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Image Upload --}}
                        {{-- <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Image</label>
                            <input type="file" name="pic_file" accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                      file:mr-4 file:rounded-md file:border-0 file:bg-lime-50 file:px-4 file:py-2
                                      file:text-sm file:font-medium file:text-lime-700
                                      hover:file:bg-lime-100 dark:file:bg-lime-900/20 dark:file:text-lime-400">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max 5MB. JPG, PNG, GIF</p>
                            @error('pic_file')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div> --}}

                        {{-- PDF Upload --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">PDF
                                Document</label>
                            <input type="file" name="pdf_path" accept=".pdf"
                                class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                      file:mr-4 file:rounded-md file:border-0 file:bg-lime-50 file:px-4 file:py-2
                                      file:text-sm file:font-medium file:text-lime-700
                                      hover:file:bg-lime-100 dark:file:bg-lime-900/20 dark:file:text-lime-400">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max 10MB. PDF only</p>
                            @error('pdf_path')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Footer --}}
                    <div class="bg-gray-50 px-6 py-4 dark:bg-gray-900 sm:flex sm:flex-row-reverse sm:gap-3">
                        <button type="submit"
                            class="inline-flex w-full justify-center rounded-md bg-lime-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-lime-700 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 sm:w-auto">
                            Save
                        </button>
                        <button @click="isOpen = false" type="button"
                            class="mt-3 inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-lime-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 sm:mt-0 sm:w-auto">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
