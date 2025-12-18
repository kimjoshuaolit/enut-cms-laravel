<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PostItem;
use App\Services\LegacyFileService;
use Illuminate\Support\Facades\Log;

class PostItemController extends Controller
{
    protected $legacyFileService;

    public function __construct(LegacyFileService $legacyFileService)
    {
        $this->legacyFileService = $legacyFileService;
    }

    public function store(Request $request)
    {

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
                'post_description2' => '',
                'post_url' => '',
                'post_type' => 'NA',
                'date_pub' => 'NA',
                'post_survey' => $validated['post_survey'],
                'post_year' => $validated['post_year'],
                'post_cat' => $validated['post_cat'],
                'status' => '1',
                'pic_file' => '',
                'pdf_path' => null,
            ];

            if ($request->hasFile('pic_file')) {
                $imagePath = $this->legacyFileService->uploadImage(
                    $request->file('pic_file'),
                    $validated['post_cat']
                );

                if ($imagePath) {
                    $data['pic_file'] = $imagePath;
                } else {
                    return redirect()->back()
                        ->with('error', 'Failed to upload Image to storage system.')
                        ->withInput();
                }
            }

            //upload pdf via API

            if ($request->hasFile('pdf_path')) {
                $pdfPath = $this->legacyFileService->uploadPdf(
                    $request->file('pdf_path'),
                    $validated['post_cat']
                );

                if ($pdfPath) {
                    $data['pdf_path'] = $pdfPath;
                } else {
                    return redirect()->back()
                        ->with('error', 'Failed to upload PDF to storage system.')
                        ->withInput();
                }
            }

            PostItem::create($data);

            return redirect()->back()->with('success', 'Post item created successfully!');
        } catch (\Exception $e) {
            Log::error('Post item creation error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create post item: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function edit($id)
    {
        try {
            $postItem = PostItem::findOrFail($id);
            return response()->json($postItem);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Item not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'post_title' => 'required|string|min:3|max:255',
            'post_description' => 'nullable|string|max:1000',
            'post_survey' => 'nullable|string|max:255',
            'post_year' => 'required|integer|min:1900|max:2100',
            'post_cat' => 'required|string',
            'pic_file' => 'nullable|image|max:5120',
            'pdf_path' => 'nullable|file|mimes:pdf|max:10240',
        ], [
            'post_title.required' => 'Title is required.',
            'post_year.required' => 'Year is required.',
            'pic_file.image' => 'The file must be an image.',
            'pic_file.max' => 'Image must not exceed 5MB.',
            'pdf_path.mimes' => 'The file must be a PDF.',
            'pdf_path.max' => 'PDF must not exceed 10MB.',
        ]);

        try {
            $postItem = PostItem::findOrFail($id);

            $data = [
                'post_title' => $validated['post_title'],
                'post_description' => $validated['post_description'] ?? '',
                'post_description2' => '',
                'post_survey' => $validated['post_survey'] ?? '',
                'post_year' => $validated['post_year'],
                'post_cat' => $validated['post_cat'],
            ];


            // Handle new image upload
            if ($request->hasFile('pic_file')) {
                // Delete old image if exists
                if ($postItem->pic_file && $postItem->pic_file !== 'NA') {
                    $this->legacyFileService->deleteFile($postItem->pic_file);
                }

                // Upload new image
                $imagePath = $this->legacyFileService->uploadImage(
                    $request->file('pic_file'),
                    $validated['post_cat']
                );

                if ($imagePath) {
                    $data['pic_file'] = $imagePath;
                }
            }

            // Handle new PDF upload
            if ($request->hasFile('pdf_path')) {
                //Delete old PDF from enutV2
                if ($postItem->pdf_path) {
                    $this->legacyFileService->deleteFile($postItem->pdf_path);
                }

                //Upload new PDF

                $pdfPath = $this->legacyFileService->uploadPdf(
                    $request->file('pdf_path'),
                    $validated['post_cat']
                );
                if ($pdfPath) {
                    $data['pdf_path'] = $pdfPath;
                }
            }

            $postItem->update($data);

            return redirect()->back()->with('success', 'Post item updated successfully!');
        } catch (\Exception $e) {
            Log::error('Post item update error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update post item: ' . $e->getMessage())
                ->withInput();
        }
    }
    public function destroy($id)
    {
        try {
            $postItem = PostItem::findOrFail($id);

            //Define enutV2 base path
            $enutV2BasePath = config('filesystem.enutv2_path', 'C:/wamp64/www/enutV2/storage/app/public');


            // Delete image file if exists
            if ($postItem->pic_filel && $postItem->pic_file !== 'NA') {
                $this->legacyFileService->deleteFile($postItem->pic_file);
            }
            // Delete PDF file if exists
            if ($postItem->pdf_path) {
                $this->legacyFileService->deleteFile($postItem->pdf_path);
            }

            // Delete database record
            $postItem->delete();

            return redirect()->back()->with('success', 'Post item deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Post item deletion error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete post item: ' . $e->getMessage());
        }
    }
}
