<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service to handle file uploads to legacy enutV2 system via API
 */
class LegacyFileService
{
    protected $baseUrl;

    public function __construct()
    {
        // URL to enutV2 API
        // Change this if enutV2 is on a different server or port
        $this->baseUrl = 'http://localhost:8000/api';
    }

    /**
     * Upload image to legacy enutV2 system
     *
     * @param UploadedFile $file The image file to upload
     * @param string $category The category (e.g., "Facts and Figures")
     * @return string|null Returns the file path on success, null on failure
     */
    public function uploadImage(UploadedFile $file, string $category): ?string
    {
        try {

            // Debug: Log what we're trying to do
            Log::info('Attempting to upload image to enutV2', [
                'file' => $file->getClientOriginalName(),
                'category' => $category,
                'api_url' => $this->baseUrl . '/upload-image',
            ]);
            // Send POST request to enutV2 API with the file
            $response = Http::attach(
                'file',                              // Field name
                file_get_contents($file->path()),    // File contents
                $file->getClientOriginalName()       // Original filename
            )->post($this->baseUrl . '/upload-image', [
                'category' => $category,
            ]);

            // Check if upload was successful
            if ($response->successful()) {
                $data = $response->json();
                return $data['path'] ?? null;
            }

            // Log error if upload failed
            Log::error('Image upload to enutV2 failed', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            // Log any exceptions
            Log::error('Image upload to enutV2 error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Upload PDF to legacy enutV2 system
     *
     * @param UploadedFile $file The PDF file to upload
     * @param string $category The category
     * @return string|null Returns the file path on success, null on failure
     */
    public function uploadPdf(UploadedFile $file, string $category): ?string
    {
        try {
            // Send POST request to enutV2 API with the file
            $response = Http::attach(
                'file',
                file_get_contents($file->path()),
                $file->getClientOriginalName()
            )->post($this->baseUrl . '/upload-pdf', [
                'category' => $category,
            ]);

            // Check if upload was successful
            if ($response->successful()) {
                $data = $response->json();
                return $data['path'] ?? null;
            }

            // Log error if upload failed
            Log::error('PDF upload to enutV2 failed', [
                'status' => $response->status(),
                'response' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            // Log any exceptions
            Log::error('PDF upload to enutV2 error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete file from legacy enutV2 system
     *
     * @param string $path The file path to delete (e.g., "storage/img/category/file.jpg")
     * @return bool Returns true on success, false on failure
     */
    public function deleteFile(string $path): bool
    {
        try {
            // Send POST request to delete the file
            $response = Http::post($this->baseUrl . '/delete-file', [
                'path' => $path,
            ]);

            return $response->successful();
        } catch (\Exception $e) {
            // Log any exceptions
            Log::error('File deletion from enutV2 error: ' . $e->getMessage());
            return false;
        }
    }
}
