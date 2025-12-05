<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostItem;
use Illuminate\Support\Facades\Storage;

class PostItemController extends Controller
{
    /**
     * Store a newly created post item
     */
    public function store(Request $request)
    {
        // Validation - same as Livewire component
        $validated = $request->validate([
            'post_title' => 'required|string|min:3|max:255',
            'post_description' => 'nullable|string|max:1000',
            'post_survey' => 'nullable|string|max:255',
            'post_year' => 'required|integer|min:1900|max:2100',
            'post_cat' => 'required|string',
            'pic_file' => 'nullable|image|max:5120', // 5MB max
            'pdf_path' => 'nullable|file|mimes:pdf|max:10240', // 10MB max
        ], [
            'post_title.required' => 'Title is required.',
            'post_year.required' => 'Year is required.',
            'pic_file.image' => 'The file must be an image.',
            'pic_file.max' => 'Image must not exceed 5MB.',
            'pdf_path.mimes' => 'The file must be a PDF.',
            'pdf_path.max' => 'PDF must not exceed 10MB.',
        ]);

        try {
            // Prepare data - same as Livewire
            $data = [
                'post_title' => $validated['post_title'],
                'post_description' => $validated['post_description'] ?? null,
                'post_description2' => '',  // ← ADD THIS LINE
                'post_url' => '',           // ← ADD THIS LINE
                'post_type' => 'NA',     // ← ADD THIS LINE
                'date_pub' => 'NA',          // ← ADD THIS LINE
                'post_survey' => $validated['post_survey'],
                'post_year' => $validated['post_year'],
                'post_cat' => $validated['post_cat'],
                'status' => '1',
            ];

            $categorySlug = strtolower(str_replace(' ', '', $validated['post_cat']));

            // Define enutV2 base path
            $enutV2BasePath = 'C:/wamp64/www/enutV2/storage/app/public';

            // Handle image upload to enutV2
            if ($request->hasFile('pic_file')) {
                $imageFile = $request->file('pic_file');
                $imageName = time() . '_' . $imageFile->getClientOriginalName();

                // Create directory if it doesn't exist
                $imageDir = $enutV2BasePath . '/img/' . $categorySlug;
                if (!file_exists($imageDir)) {
                    mkdir($imageDir, 0755, true);
                }

                // Move file to enutV2 directory
                $imageFile->move($imageDir, $imageName);

                // Store path for database (relative path that matches old structure)
                $data['pic_file'] = 'storage/img/' . $categorySlug . '/' . $imageName;
            }

            // Handle PDF upload to enutV2
            if ($request->hasFile('pdf_path')) {
                $pdfFile = $request->file('pdf_path');
                $pdfName = time() . '_' . $pdfFile->getClientOriginalName();

                // Create directory if it doesn't exist
                $pdfDir = $enutV2BasePath . '/pdf/' . $categorySlug;
                if (!file_exists($pdfDir)) {
                    mkdir($pdfDir, 0755, true);
                }

                // Move file to enutV2 directory
                $pdfFile->move($pdfDir, $pdfName);

                // Store path for database (relative path that matches old structure)
                $data['pdf_path'] = 'storage/pdf/' . $categorySlug . '/' . $pdfName;
            }

            // Create post item - same as Livewire
            PostItem::create($data);

            // Success message - same as Livewire
            return redirect()->back()->with('success', 'Post item created successfully!');
        } catch (\Exception $e) {
            // Error handling - same as Livewire
            \Log::error('Post item creation error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create post item: ' . $e->getMessage())
                ->withInput();
        }
    }
}
