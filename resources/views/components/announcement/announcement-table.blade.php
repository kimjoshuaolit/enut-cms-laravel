<div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800 flex items-center justify-between">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white">
            Announcements
            <span class="ml-2 text-sm font-normal text-gray-500">
                ({{ $announcements->count() }} total)
            </span>
        </h3>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-sm text-left">
            <thead class="bg-gray-50 dark:bg-gray-800 text-xs uppercase text-gray-500 dark:text-gray-400">
                <tr>
                    <th class="px-6 py-3">Image</th>
                    <th class="px-6 py-3">Title</th>
                    <th class="px-6 py-3">Category</th>
                    <th class="px-6 py-3">Date</th>
                    <th class="px-6 py-3">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                @forelse($announcements as $announcement)
                    <tr class="hover:bg-gray-50 dark:hover:bg-white/5">
                        {{-- Image --}}
                        <td class="px-6 py-4">
                            @if ($announcement->ann_media)
                                <img src="{{ old_img_path($announcement->ann_media) }}"
                                    alt="{{ $announcement->ann_title }}" class="w-16 h-16 object-cover rounded-lg">
                            @else
                                <div class="w-16 h-16 bg-gray-100 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                </div>
                            @endif
                        </td>

                        {{-- Title --}}
                        <td class="px-6 py-4">
                            <p class="font-medium text-gray-900 dark:text-white line-clamp-2 max-w-xs">
                                {{ $announcement->ann_title }}
                            </p>
                            <p class="text-xs text-gray-500 mt-1 line-clamp-2 max-w-xs">
                                {{ Str::limit($announcement->ann_article, 80) }}
                            </p>
                        </td>

                        {{-- Category --}}
                        <td class="px-6 py-4">
                            <span
                                class="inline-flex items-center rounded-full bg-lime-50 px-2.5 py-0.5 text-xs font-medium text-lime-700 dark:bg-lime-500/15 dark:text-lime-400">
                                {{ $announcement->ann_category }}
                            </span>
                        </td>

                        {{-- Date --}}
                        <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($announcement->ann_date)->format('M d, Y') }}
                        </td>

                        {{-- Actions --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                {{-- Edit --}}
                                <button
                                    @click="$dispatch('open-announcement-edit', { id: {{ $announcement->ann_id }} })"
                                    class="text-yellow-500 hover:text-yellow-600 p-1 rounded">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                </button>

                                {{-- Delete --}}
                                <form action="{{ route('announcement.destroy', $announcement->ann_id) }}" method="POST"
                                    onsubmit="return confirm('Are you sure you want to delete this announcement?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-600 p-1 rounded">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                            No announcements found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
