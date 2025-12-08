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
                ->where('cat_year', $validated['cat_year'])
                ->where('cat_title', $validated['cat_title'])
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
    public function edit($id)
    {
        try {
            $galleryItem = Gallery::findOrFail($id);
            return response()->json($galleryItem);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Item not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'title' => 'required|string|in:' . implode(',', Gallery::TITLES),
            'area' => 'required|string|in:' . implode(',', Gallery::PROVINCES),
            'cat_title' => 'required|string',
            'cat_year' => 'required|integer|min:1900|max:2100',
            'file_path' => 'nullable|image|max:5120',
        ], [
            'title.required' => 'Title is required.',
            'title.in' => 'Invalid title selected.',
            'area.required' => 'Area is required.',
            'area.in' => 'Invalid province selected.',
            'cat_title.required' => 'Category title is required.',
            'cat_year.required' => 'Category year is required.',
            'file_path.image' => 'The file must be an image.',
            'file_path.max' => 'Image must not exceed 5MB.',
        ]);

        try {
            $galleryItem = Gallery::findOrFail($id);

            // Check if title, area, cat_year, or cat_title changed (group changed)
            $groupChanged = (
                $galleryItem->title !== $validated['title'] ||
                $galleryItem->area !== $validated['area'] ||
                $galleryItem->cat_year !== $validated['cat_year'] ||
                $galleryItem->cat_title !== $validated['cat_title']
            );
            $data = [
                'title' => $validated['title'],
                'area' => $validated['area'],
                'cat_title' => $validated['cat_title'],
                'cat_year' => $validated['cat_year'],
            ];

            // If group changed, assign new page number
            if ($groupChanged) {
                // Get the highest page number in the NEW group
                $lastPageEntry = Gallery::where('title', $validated['title'])
                    ->where('area', $validated['area'])
                    ->where('cat_year', $validated['cat_year'])
                    ->where('cat_title', $validated['cat_title'])
                    ->where('id', '!=', $id) // Exclude current item
                    ->orderBy('page_no', 'desc')
                    ->first();

                // Extract number from "Page X" or start at 0
                $lastPageNumber = 0;
                if ($lastPageEntry && $lastPageEntry->page_no) {
                    preg_match('/\d+/', $lastPageEntry->page_no, $matches);
                    $lastPageNumber = $matches[0] ?? 0;
                }

                // Assign next page number
                $data['page_no'] = 'Page ' . ($lastPageNumber + 1);
            }
            // If group didn't change, keep the same page number (don't update it)

            $enutV2BasePath = config('filesystems.enutv2_path', 'C:/wamp64/www/enutV2/storage/app/public');

            // Handle new image upload
            if ($request->hasFile('file_path')) {
                // Delete old image if exists
                if ($galleryItem->file_path && $galleryItem->file_path !== 'NA') {
                    $oldImagePath = $enutV2BasePath . '/' . str_replace('storage/', '', $galleryItem->file_path);
                    if (file_exists($oldImagePath)) {
                        unlink($oldImagePath);
                    }
                }

                // Upload new image
                $imageFile = $request->file('file_path');
                $imageName = time() . '_' . $imageFile->getClientOriginalName();
                $areaSlug = strtolower(str_replace(' ', '', $validated['area']));
                $imageDir = $enutV2BasePath . '/img/infographics/' . $validated['cat_year'] . '/' . $areaSlug;

                if (!file_exists($imageDir)) {
                    mkdir($imageDir, 0755, true);
                }

                $imageFile->move($imageDir, $imageName);
                $data['file_path'] = 'storage/img/infographics/' . $validated['cat_year'] . '/' . $areaSlug . '/' . $imageName;
            }

            $galleryItem->update($data);
            if ($groupChanged) {
                return redirect()->back()->with('success', 'Gallery item updated successfully! New page number: ' . $data['page_no']);
            } else {
                return redirect()->back()->with('success', 'Gallery item updated successfully!');
            }

            return redirect()->back()->with('success', 'Gallery item updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Gallery update error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update gallery item: ' . $e->getMessage())
                ->withInput();
        }
    }
    public function destroy($id)
    {
        try {
            $galleryItem = Gallery::findOrFail($id);

            // Define enutV2 base path
            $enutV2BasePath = config('filesystems.enutv2_path', 'C:/wamp64/www/enutV2/storage/app/public');

            // Delete image file if exists
            if ($galleryItem->file_path && $galleryItem->file_path !== 'NA') {
                $imagePath = $enutV2BasePath . '/' . str_replace('storage/', '', $galleryItem->file_path);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            // Delete database record
            $galleryItem->delete();

            return redirect()->back()->with('success', 'Gallery item deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Gallery deletion error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete gallery item.');
        }
    }
}
