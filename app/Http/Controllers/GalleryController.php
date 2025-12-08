<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|in:' . implode(',', Gallery::TITLES),
            'area' => 'required|string|in:' . implode(',', Gallery::PROVINCES),
            'cat_title' => 'required|string',
            'cat_year' => 'required|integer|min:1900|max:2100',
            'images' => 'required|array|min:1|max:20',
            'images.*' => 'required|image|max:5120',
        ], [
            'title.required' => 'Title is required.',
            'title.in' => 'Invalid title selected.',
            'area.required' => 'Area is required.',
            'area.in' => 'Invalid province selected.',
            'cat_title.required' => 'Category title is required.',
            'cat_year.required' => 'Category year is required.',
            'images.required' => 'At least one image is required.',
            'images.min' => 'At least one image is required.',
            'images.max' => 'Maximum 20 images allowed.',
            'images.*.image' => 'All files must be images.',
            'images.*.max' => 'Each image must not exceed 5MB.',
        ]);

        try {
            // Get the highest page number (extract number from "Page X" format)
            $lastPageEntry = Gallery::where('title', $validated['title'])
                ->where('area', $validated['area'])
                ->orderBy('page_no', 'desc')
                ->first();

            // Extract number from "Page X" or start at 0
            $lastPageNumber = 0;
            if ($lastPageEntry && $lastPageEntry->page_no) {
                preg_match('/\d+/', $lastPageEntry->page_no, $matches);
                $lastPageNumber = $matches[0] ?? 0;
            }

            // Define enutV2 base path
            $enutV2BasePath = config('filesystems.enutv2_path', 'C:/wamp64/www/enutV2/storage/app/public');

            // Create directory path based on title and area
            $areaSlug = strtolower(str_replace(' ', '', $validated['area']));
            $imageDir = $enutV2BasePath . '/img/infographics/' . $validated['cat_year'] . '/' . $areaSlug;

            // Create directory if it doesn't exist
            if (!file_exists($imageDir)) {
                mkdir($imageDir, 0755, true);
            }

            // Process each uploaded image
            $savedCount = 0;
            foreach ($request->file('images') as $index => $imageFile) {
                $lastPageNumber++; // Increment page number for each image

                $imageName = time() . '_' . $lastPageNumber . '_' . $imageFile->getClientOriginalName();

                // Move file to enutV2 directory
                $imageFile->move($imageDir, $imageName);

                // Store path for database
                $filePath = 'storage/img/infographics/' . $validated['cat_year'] . '/' . $areaSlug . '/' . $imageName;

                // Create gallery entry with "Page X" format
                Gallery::create([
                    'title' => $validated['title'],
                    'area' => $validated['area'],
                    'page_no' => 'Page ' . $lastPageNumber,  // ← Add "Page" prefix
                    'cat_title' => $validated['cat_title'],
                    'cat_year' => $validated['cat_year'],
                    'file_path' => $filePath,
                ]);

                $savedCount++;
            }

            return redirect()->back()->with('success', "$savedCount image(s) uploaded successfully!");
        } catch (\Exception $e) {
            \Log::error('Gallery upload error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to upload images: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function updateOrder(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array',
            'items.*.id' => 'required|exists:gallery,id',
            'items.*.page_no' => 'required|string',
        ]);

        try {
            foreach ($validated['items'] as $item) {
                Gallery::where('id', $item['id'])->update(['page_no' => $item['page_no']]);
            }

            return response()->json(['success' => true, 'message' => 'Order updated successfully!']);
        } catch (\Exception $e) {
            \Log::error('Gallery order update error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update order.'], 500);
        }
    }
}
