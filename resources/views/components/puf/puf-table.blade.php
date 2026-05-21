<div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="px-5 py-4 sm:px-6 border-b border-gray-100 dark:border-gray-800">
        <h3 class="text-xl font-semibold text-gray-800 dark:text-white">PUF Items</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
            Total: {{ $postItems->total() }} item{{ $postItems->total() !== 1 ? 's' : '' }}
        </p>
    </div>

    @if ($postItems->count() > 0)
        <div class="max-w-full overflow-x-auto">
            <table class="w-full min-w-[1100px]">
                <thead>
                    <tr class="border-b border-gray-100 dark:border-gray-800">
                        <th class="px-5 py-3 text-left sm:px-6">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Title</p>
                        </th>
                        <th class="px-5 py-3 text-left sm:px-6">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Survey</p>
                        </th>
                        <th class="px-5 py-3 text-left sm:px-6">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Type</p>
                        </th>
                        <th class="px-5 py-3 text-left sm:px-6">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Year</p>
                        </th>
                        <th class="px-5 py-3 text-left sm:px-6">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">PUF File</p>
                        </th>
                        <th class="px-5 py-3 text-left sm:px-6">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">PDF</p>
                        </th>
                        <th class="px-5 py-3 text-left sm:px-6">
                            <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Actions</p>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($postItems as $item)
                        <tr
                            class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors">

                            {{-- Title --}}
                            <td class="px-5 py-4 sm:px-6">
                                <span class="block font-medium text-gray-800 text-theme-sm dark:text-white/90">
                                    {{ $item->post_title }}
                                </span>
                                @if ($item->post_description)
                                    <span class="block text-gray-500 text-theme-xs dark:text-gray-400 mt-1">
                                        {{ Str::limit($item->post_description, 50) }}
                                    </span>
                                @endif
                            </td>

                            {{-- Survey --}}
                            <td class="px-5 py-4 sm:px-6">
                                <span
                                    class="inline-flex items-center rounded-full bg-blue-50 px-2.5 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-500/15 dark:text-blue-400">
                                    {{ $item->post_survey ?? 'N/A' }}
                                </span>
                            </td>

                            {{-- Type --}}
                            <td class="px-5 py-4 sm:px-6">
                                <span
                                    class="inline-flex items-center rounded-full bg-purple-50 px-2.5 py-0.5 text-xs font-medium text-purple-700 dark:bg-purple-500/15 dark:text-purple-400">
                                    {{ $item->post_type ?? 'N/A' }}
                                </span>
                            </td>

                            {{-- Year --}}
                            <td class="px-5 py-4 sm:px-6">
                                <span
                                    class="text-gray-600 text-theme-sm dark:text-gray-400">{{ $item->post_year }}</span>
                            </td>

                            {{-- PUF File --}}
                            <td class="px-5 py-4 sm:px-6">
                                @if (isset($pufFiles[$item->id]))
                                    <a href="{{ config('services.enutv2.base_url') }}/{{ $pufFiles[$item->id]->file_path }}"
                                        target="_blank"
                                        class="inline-flex items-center gap-1 text-lime-600 hover:text-lime-700 dark:text-lime-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                        </svg>
                                        <span class="text-theme-xs">Download</span>
                                    </a>
                                @else
                                    <span class="text-gray-400 text-theme-xs">No file</span>
                                @endif
                            </td>

                            {{-- PDF --}}
                            <td class="px-5 py-4 sm:px-6">
                                @if ($item->pdf_path)
                                    <a href="{{ old_pdf_path($item->pdf_path) }}" target="_blank"
                                        class="inline-flex items-center gap-1 text-blue-600 hover:text-blue-700 dark:text-blue-400">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                        </svg>
                                        <span class="text-theme-xs">View</span>
                                    </a>
                                @else
                                    <span class="text-gray-400 text-theme-xs">No PDF</span>
                                @endif
                            </td>

                            {{-- Actions --}}
                            <td class="px-5 py-4 sm:px-6">
                                <div class="flex items-center gap-2">
                                    <button type="button"
                                        @click="$dispatch('open-puf-edit', { id: {{ $item->id }} })"
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-yellow-50 text-yellow-600 hover:bg-yellow-100 dark:bg-yellow-500/15 dark:text-yellow-400 dark:hover:bg-yellow-500/25 transition-colors">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </button>

                                    <form action="{{ route('puf.destroy', $item->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure you want to delete this PUF item?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 dark:bg-red-500/15 dark:text-red-400 dark:hover:bg-red-500/25 transition-colors">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
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
        @if ($postItems->hasPages())
            <div class="px-5 py-4 sm:px-6 border-t border-gray-100 dark:border-gray-800">
                {{ $postItems->links() }}
            </div>
        @endif
    @else
        <div class="px-5 py-12 sm:px-6 text-center">
            <h3 class="mt-4 text-lg font-medium text-gray-800 dark:text-white">No PUF items found</h3>
        </div>
    @endif
</div>
