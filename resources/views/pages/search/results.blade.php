@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Search Results" />

    <div class="grid grid-cols-1 gap-6">
        {{-- Search Summary --}}
        <div class="rounded-xl border border-gray-200 bg-white p-6 dark:border-gray-800 dark:bg-white/[0.03]">
            <h2 class="text-2xl font-semibold text-gray-900 dark:text-white">
                Search results for "<span class="text-lime-600 dark:text-lime-400">{{ $query }}</span>"
            </h2>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Found {{ $totalResults }} result{{ $totalResults !== 1 ? 's' : '' }}
            </p>
        </div>

        {{-- Post Items Results --}}
        @if ($postItems->count() > 0)
            <div class="space-y-4">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600 dark:text-blue-400" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Posts ({{ $postItems->count() }})
                </h3>

                {{-- Simple Table for Search Results --}}
                <div
                    class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="max-w-full overflow-x-auto">
                        <table class="w-full min-w-[800px]">
                            <thead class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-5 py-3 text-left">
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Title</p>
                                    </th>
                                    <th class="px-5 py-3 text-left">
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Category</p>
                                    </th>
                                    <th class="px-5 py-3 text-left">
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Year</p>
                                    </th>
                                    <th class="px-5 py-3 text-left">
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Actions</p>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($postItems as $item)
                                    <tr id="item-{{ $item->id }}"
                                        class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors scroll-mt-24">
                                        <td class="px-5 py-4">
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $item->post_title }}</p>
                                            @if ($item->post_description)
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                    {{ Str::limit($item->post_description, 80) }}</p>
                                            @endif
                                        </td>
                                        <td class="px-5 py-4">
                                            <span
                                                class="inline-flex items-center rounded-full bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 dark:bg-blue-900/20 dark:text-blue-400">
                                                {{ $item->post_cat }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <span
                                                class="text-sm text-gray-600 dark:text-gray-400">{{ $item->post_year }}</span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <a href="{{ route('search.view-item', ['type' => 'post', 'id' => $item->id]) }}"
                                                class="inline-flex items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                                View
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        </td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- Gallery Results --}}
        @if ($galleryItems->count() > 0)
            <div class="space-y-4">
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-purple-600 dark:text-purple-400"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    Infographics ({{ $galleryItems->count() }})
                </h3>

                {{-- Simple Table for Search Results --}}
                <div
                    class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                    <div class="max-w-full overflow-x-auto">
                        <table class="w-full min-w-[800px]">
                            <thead class="border-b border-gray-100 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50">
                                <tr>
                                    <th class="px-5 py-3 text-left">
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Title</p>
                                    </th>
                                    <th class="px-5 py-3 text-left">
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Area</p>
                                    </th>
                                    <th class="px-5 py-3 text-left">
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Page</p>
                                    </th>
                                    <th class="px-5 py-3 text-left">
                                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Actions</p>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($galleryItems as $item)
                                    <tr id="item-{{ $item->id }}"
                                        class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-white/[0.02] transition-colors scroll-mt-24">
                                        <td class="px-5 py-4">
                                            <p class="font-medium text-gray-900 dark:text-white">{{ $item->title }}</p>
                                        </td>
                                        <td class="px-5 py-4">
                                            <span
                                                class="inline-flex items-center rounded-full bg-purple-50 px-2 py-1 text-xs font-medium text-purple-700 dark:bg-purple-900/20 dark:text-purple-400">
                                                {{ $item->area }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <span
                                                class="text-sm text-gray-600 dark:text-gray-400">{{ $item->page_no }}</span>
                                        </td>
                                        <td class="px-5 py-4">
                                            <a href="{{ route('search.view-item', ['type' => 'gallery', 'id' => $item->id]) }}"
                                                class="inline-flex items-center gap-1 text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                                View
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none"
                                                    viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endif

        {{-- No Results --}}
        @if ($totalResults === 0)
            <div
                class="rounded-xl border border-gray-200 bg-white p-12 text-center dark:border-gray-800 dark:bg-white/[0.03]">
                <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-16 w-16 text-gray-400" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No results found</h3>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Try searching with different keywords
                </p>
            </div>
        @endif
    </div>
@endsection
