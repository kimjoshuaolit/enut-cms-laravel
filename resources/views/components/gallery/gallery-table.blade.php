<div x-data="{
    getStatusClass(year) {
        const currentYear = new Date().getFullYear();
        if (year == currentYear) {
            return 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500';
        } else if (year == currentYear - 1) {
            return 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400';
        } else {
            return 'bg-gray-50 text-gray-700 dark:bg-gray-500/15 dark:text-gray-400';
        }
    }
}">
    <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        {{-- Header Section --}}
        <div class="px-5 py-4 sm:px-6 border-b border-gray-100 dark:border-gray-800">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-xl font-semibold text-gray-800 dark:text-white">{{ $category }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                        Total: {{ $galleryItem->total() }} item{{ $galleryItem->total() !== 1 ? 's' : '' }}
                        @if ($galleryItem->total() > 0)
                            (Showing {{ $galleryItem->firstItem() }}-{{ $galleryItem->lastItem() }})
                        @endif
                    </p>
                </div>
            </div>
        </div>

        @if ($galleryItem->count() > 0)
            <div class="max-w-full overflow-x-auto custom-scrollbar">
                <table class="w-full min-w-[1102px]">
                    <thead>
                        <tr class="border-b border-gray-100 dark:border-gray-800">
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                    Page No.
                                </p>
                            </th>
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                    Title & Area
                                </p>
                            </th>
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                    Survey
                                </p>
                            </th>
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                    Year
                                </p>
                            </th>
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                    Image
                                </p>
                            </th>
                            <th class="px-5 py-3 text-left sm:px-6">
                                <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">
                                    Actions
                                </p>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($galleryItem as $item)
                            <tr id ="item-{{ $item->id }}"
                                class="scroll-mt-24 border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">
                                {{-- Page Number --}}
                                <td class="px-5 py-4 sm:px-6">
                                    <span class="font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                        {{ $item->page_no }}
                                    </span>
                                </td>

                                {{-- Title & Area --}}
                                <td class="px-5 py-4 sm:px-6">
                                    <div>
                                        <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                            {{ $item->title }}
                                        </span>
                                        @if ($item->area)
                                            <span class="block text-gray-500 text-theme-xs dark:text-gray-400 mt-1">
                                                {{ $item->area }}
                                            </span>
                                        @endif
                                    </div>
                                </td>

                                {{-- Survey --}}
                                <td class="px-5 py-4 sm:px-6">
                                    <p class="text-gray-500 text-theme-sm dark:text-gray-400">
                                        {{ $item->cat_title ?? 'N/A' }}
                                    </p>
                                </td>

                                {{-- Year --}}
                                <td class="px-5 py-4 sm:px-6">
                                    <p class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium"
                                        :class="getStatusClass({{ $item->cat_year }})">
                                        {{ $item->cat_year }}
                                    </p>
                                </td>

                                {{-- Image --}}
                                <td class="px-5 py-4 sm:px-6">
                                    @if ($item->file_path && $item->file_path !== 'NA')
                                        <div
                                            class="w-12 h-12 overflow-hidden rounded-lg border-2 border-gray-200 dark:border-gray-700">
                                            <img src="{{ old_img_path($item->file_path) }}" alt="{{ $item->title }}"
                                                class="w-full h-full object-cover">
                                        </div>
                                    @else
                                        <div
                                            class="w-12 h-12 flex items-center justify-center rounded-lg bg-gray-100 dark:bg-gray-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-400"
                                                fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    @endif
                                </td>

                                {{-- Actions --}}
                                <td class="px-5 py-4 sm:px-6">
                                    <div class="flex items-center gap-2">
                                        {{-- <a href="#"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 dark:bg-blue-500/15 dark:text-blue-400 dark:hover:bg-blue-500/25 transition-colors"
                                            title="View">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </a> --}}
                                        {{-- EDIT FORM --}}
                                        <button type="button"
                                            @click="$dispatch('open-gallery-edit', { id: {{ $item->id }} })"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 dark:bg-yellow-500/15 dark:text-yellow-400 dark:hover:bg-yellow-500/25 transition-colors"
                                            title="Edit">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>

                                        {{-- Delete Form --}}
                                        <form action="{{ route('gallery.destroy', $item->id) }}" method="POST"
                                            class="inline-block"
                                            onsubmit="return confirm('Are you sure you want to delete this image? This will also delete the file.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-500/15 dark:text-red-400 dark:hover:bg-red-500/25 transition-colors"
                                                title="Delete">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        stroke-width="2"
                                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if ($galleryItem->hasPages())
                <div class="px-5 py-4 sm:px-6 border-t border-gray-100 dark:border-gray-800">
                    {{ $galleryItem->links() }}
                </div>
            @endif
        @else
            {{-- Empty State --}}
            <div class="px-5 py-12 sm:px-6 text-center">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 mx-auto text-gray-400 dark:text-gray-600"
                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-800 dark:text-white">No items found</h3>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    No gallery items found for "{{ $category }}"
                </p>
            </div>
        @endif
    </div>
</div>
