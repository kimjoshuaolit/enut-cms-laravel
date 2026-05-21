<div x-data="{
    isOpen: false,
    itemId: null,
    surveyOption: 'existing',
    formData: {
        post_title: '',
        post_description: '',
        post_description2: '',
        post_survey: '',
        post_type: '',
        post_year: '',
        date_pub: '',
        existing_survey: '',
        current_pic_file: '',
        current_pdf_path: '',
        current_puf_file: '',
    },

    async openEdit(id) {
        try {
            const response = await fetch(`/puf/${id}/edit`);
            const data = await response.json();
            const item = data.post_item;
            const puf = data.puf_csv;

            this.itemId = id;
            this.formData.post_title = item.post_title || '';
            this.formData.post_description = item.post_description || '';
            this.formData.post_description2 = item.post_description2 || '';
            this.formData.post_survey = item.post_survey || '';
            this.formData.post_type = item.post_type || '';
            this.formData.post_year = item.post_year || '';
            this.formData.date_pub = item.date_pub || '';
            this.formData.current_pic_file = item.pic_file || '';
            this.formData.current_pdf_path = item.pdf_path || '';
            this.formData.current_puf_file = puf ? puf.file_path : '';

            // Find matching survey
            const surveys = data.surveys;
            // Default to existing
            this.surveyOption = 'existing';
            this.isOpen = true;
        } catch (error) {
            console.error('Error loading PUF item:', error);
            alert('Failed to load PUF item data');
        }
    },

    closeModal() {
        this.isOpen = false;
        this.itemId = null;
    }
}" @open-puf-edit.window="openEdit($event.detail.id)">

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

                <form :action="`/puf/${itemId}`" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    {{-- Header --}}
                    <div class="border-b border-gray-200 bg-white px-6 py-4 dark:border-gray-700 dark:bg-gray-800">
                        <div class="flex items-center justify-between">
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Edit PUF Item</h3>
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
                    <div class="max-h-[70vh] overflow-y-auto bg-white px-6 py-4 dark:bg-gray-800 space-y-4">

                        {{-- Title --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Title <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="post_title" x-model="formData.post_title" required
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                        </div>

                        {{-- Description --}}
                        <div>
                            <label
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                            <textarea name="post_description" x-model="formData.post_description" rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"></textarea>
                        </div>

                        {{-- Description 2 --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description
                                2</label>
                            <textarea name="post_description2" x-model="formData.post_description2" rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"></textarea>
                        </div>

                        {{-- Survey Type & Post Type --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Survey <span class="text-red-500">*</span>
                                </label>
                                <select name="post_survey" x-model="formData.post_survey" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                                    <option value="">Select Survey</option>
                                    @foreach (['Updating', 'ENNS', 'NNS', 'RNAS'] as $survey)
                                        <option value="{{ $survey }}">{{ $survey }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Type <span class="text-red-500">*</span>
                                </label>
                                <select name="post_type" x-model="formData.post_type" required
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                                    <option value="">Select Type</option>
                                    @foreach (['biochemical', 'maternal', 'food', 'socio', 'anthrop', 'iycf', 'dietary', 'dietary_indiv', 'socio_dem', 'clinical', 'socio1', 'food1'] as $type)
                                        <option value="{{ $type }}">{{ $type }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Year & Date Published --}}
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Year <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="post_year" x-model="formData.post_year" required
                                    min="1900" max="2100"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date
                                    Published</label>
                                <input type="text" name="date_pub" x-model="formData.date_pub"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                                    placeholder="e.g., 2024">
                            </div>
                        </div>

                        {{-- Survey Category --}}
                        <div class="rounded-lg border border-gray-200 dark:border-gray-700 p-4 space-y-3">
                            <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300">
                                Survey Category <span class="text-red-500">*</span>
                            </label>
                            <div class="flex gap-4">
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="survey_option" value="existing" x-model="surveyOption"
                                        class="text-lime-600 focus:ring-lime-500">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Select Existing</span>
                                </label>
                                <label class="flex items-center gap-2 cursor-pointer">
                                    <input type="radio" name="survey_option" value="new" x-model="surveyOption"
                                        class="text-lime-600 focus:ring-lime-500">
                                    <span class="text-sm text-gray-700 dark:text-gray-300">Create New</span>
                                </label>
                            </div>

                            <div x-show="surveyOption === 'existing'">
                                <select name="existing_survey" x-model="formData.existing_survey"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm">
                                    <option value="">Select Survey Category</option>
                                    @foreach ($surveys as $survey)
                                        <option value="{{ $survey->id }}">
                                            {{ $survey->sub_category }} ({{ $survey->value }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div x-show="surveyOption === 'new'">
                                <input type="text" name="new_survey_name"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-lime-500 focus:ring-lime-500 dark:border-gray-600 dark:bg-gray-700 dark:text-white sm:text-sm"
                                    placeholder="e.g., 2018-2019 Updating Survey">
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">This will be used as the
                                    folder name.</p>
                            </div>
                        </div>

                        {{-- Current PUF File --}}
                        <div x-show="formData.current_puf_file">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current PUF
                                File</label>
                            <a :href="`{{ config('services.enutv2.base_url') }}/${formData.current_puf_file}`"
                                target="_blank"
                                class="mt-1 inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                <span x-text="formData.current_puf_file.split('/').pop()"></span>
                            </a>
                        </div>

                        {{-- New PUF File --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Replace PUF File <span class="text-gray-500 text-xs">(Optional)</span>
                            </label>
                            <input type="file" name="puf_file" accept=".zip,.rar"
                                class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                      file:mr-4 file:rounded-md file:border-0 file:bg-lime-50 file:px-4 file:py-2
                                      file:text-sm file:font-medium file:text-lime-700
                                      hover:file:bg-lime-100 dark:file:bg-lime-900/20 dark:file:text-lime-400">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max 100MB. ZIP or RAR. Leave empty
                                to keep current file.</p>
                        </div>

                        {{-- Current Image --}}
                        <div x-show="formData.current_pic_file && formData.current_pic_file !== 'NA'">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current
                                Image</label>
                            <img :src="`{{ config('services.enutv2.base_url') }}/${formData.current_pic_file}`"
                                class="mt-1 h-32 w-32 object-cover rounded-lg border-2 border-gray-300">
                        </div>

                        {{-- New Image --}}
                        {{-- <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Change Image <span class="text-gray-500 text-xs">(Optional)</span>
                            </label>
                            <input type="file" name="pic_file" accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                      file:mr-4 file:rounded-md file:border-0 file:bg-lime-50 file:px-4 file:py-2
                                      file:text-sm file:font-medium file:text-lime-700
                                      hover:file:bg-lime-100 dark:file:bg-lime-900/20 dark:file:text-lime-400">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max 5MB. Leave empty to keep
                                current image.</p>
                        </div> --}}

                        {{-- Current PDF --}}
                        <div x-show="formData.current_pdf_path">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current
                                PDF</label>
                            <a :href="`{{ config('services.enutv2.base_url') }}/${formData.current_pdf_path}`"
                                target="_blank"
                                class="mt-1 inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                                View Current PDF
                            </a>
                        </div>

                        {{-- New PDF --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                                Change PDF <span class="text-gray-500 text-xs">(Optional)</span>
                            </label>
                            <input type="file" name="pdf_path" accept=".pdf"
                                class="mt-1 block w-full text-sm text-gray-500 dark:text-gray-400
                                      file:mr-4 file:rounded-md file:border-0 file:bg-lime-50 file:px-4 file:py-2
                                      file:text-sm file:font-medium file:text-lime-700
                                      hover:file:bg-lime-100 dark:file:bg-lime-900/20 dark:file:text-lime-400">
                            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Max 10MB. Leave empty to keep
                                current PDF.</p>
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
