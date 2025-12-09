<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostItem;
use App\Models\Gallery;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q');

        if (!$query || strlen($query) < 2) {
            return redirect()->back()->with('info', 'Please enter at least 2 characters to search.');
        }

        // Search PostItems
        $postItems = PostItem::where('post_title', 'LIKE', "%{$query}%")
            ->orWhere('post_description', 'LIKE', "%{$query}%")
            ->orWhere('post_survey', 'LIKE', "%{$query}%")
            ->orWhere('post_cat', 'LIKE', "%{$query}%")
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'posts');

        // Search Gallery
        $galleryItems = Gallery::where('title', 'LIKE', "%{$query}%")
            ->orWhere('area', 'LIKE', "%{$query}%")
            ->orWhere('cat_title', 'LIKE', "%{$query}%")
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'gallery');

        $totalResults = $postItems->total() + $galleryItems->total();

        return view('pages.search.results', compact('query', 'postItems', 'galleryItems', 'totalResults'));
    }

    public function quickSearch(Request $request)
    {
        $query = $request->input('q');

        if (!$query || strlen($query) < 2) {
            return response()->json([]);
        }

        // Get top 5 results from each
        $postItems = PostItem::where('post_title', 'LIKE', "%{$query}%")
            ->orWhere('post_cat', 'LIKE', "%{$query}%")
            ->select('id', 'post_title', 'post_cat', 'post_year')
            ->limit(5)
            ->get()
            ->map(function ($item) {

                $categoryRouteMap = [
                    'Facts and Figures' => 'factsandfigure',
                    'Monograph' => 'monograph',
                    'Presentation' => 'presentation',
                    'Infographics' => 'infographics',
                    'PUF' => 'puf',
                ];

                return [
                    'type' => 'post',
                    'id' => $item->id,
                    'title' => $item->post_title,
                    'category' => $item->post_cat,
                    'year' => $item->post_year,
                    'route' => $categoryRouteMap[$item->post_cat] ?? 'factsandfigure',
                ];
            });

        $galleryItems = Gallery::where('title', 'LIKE', "%{$query}%")
            ->orWhere('area', 'LIKE', "%{$query}%")
            ->select('id', 'title', 'area', 'page_no')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'gallery',
                    'id' => $item->id,
                    'title' => $item->title,
                    'area' => $item->area,
                    'page' => $item->page_no,
                ];
            });

        $results = $postItems->concat($galleryItems);

        return response()->json($results);
    }

    /**
     * Redirect to the correct page with the item highlighted
     */
    public function viewItem($type, $id)
    {
        if ($type === 'post') {
            $item = PostItem::findOrFail($id);

            // Normalize category name
            $category = trim($item->post_cat);

            // Map category to route
            $categoryRouteMap = [
                'Facts and Figures' => 'factsandfigure',
                'Monograph' => 'monograph',
                'Research Papers' => 'research-papers',
                'Presentation' => 'presentation',
            ];

            // Get route (case-insensitive)
            $route = null;
            foreach ($categoryRouteMap as $key => $value) {
                if (strcasecmp($key, $category) === 0) { // case-insensitive comparison
                    $route = $value;
                    break;
                }
            }

            if (!$route) {
                \Log::warning("Unknown category: '{$category}'");
                return redirect()->back()->with('error', "Category not found.");
            }

            // SIMPLE approach: Get all items in this category, ordered the same way as the table
            $allItems = PostItem::where('post_cat', $category)
                ->orderBy('created_at', 'desc')
                ->orderBy('id', 'desc')
                ->pluck('id')
                ->toArray();

            // Find position of current item in the array
            $position = array_search($item->id, $allItems);

            if ($position === false) {
                // Item not found, just go to first page
                $page = 1;
            } else {
                // Calculate page (position is 0-indexed, so add 1)
                $perPage = 10;
                $page = floor($position / $perPage) + 1;
            }

            return redirect()->to("/{$route}?page={$page}#item-{$id}");
        } elseif ($type === 'gallery') {
            $item = Gallery::findOrFail($id);

            // SIMPLE approach: Get all items in this category, ordered the same way as the table
            $allItems = Gallery::orderBy('created_at', 'asc')
                ->orderBy('id', 'desc')
                ->pluck('id')
                ->toArray();

            // Find position of current item in the array
            $position = array_search($item->id, $allItems);

            if ($position === false) {
                // Item not found, just go to first page
                $page = 1;
            } else {
                // Calculate page (position is 0-indexed, so add 1)
                $perPage = 10;
                $page = floor($position / $perPage) + 1;
            }

            return redirect()->to("/infographics?page={$page}#item-{$id}");
        }

        return redirect()->back()->with('error', 'Invalid item type.');
    }
}
