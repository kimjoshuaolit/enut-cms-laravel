<?php

if (!function_exists('old_storage_path')) {
    /**
     * Convert old enutV2 storage path to new symlink path
     *
     * @param string|null $path
     * @return string
     */
    function old_img_path(?string $path): string
    {
        if (!$path) {
            return '';
        }

        // Replace 'storage/' with 'enutV2/'
        $newPath = str_replace('storage/', 'enutV2/storage/app/public/', $path);

        return asset($newPath);
    }

    function old_pdf_path(?string $path): string
    {
        if (!$path) {
            return '';
        }

        // Replace 'storage/' with 'enutV2/'
        $newPath = str_replace('storage/', 'enutV2/storage/app/public/', $path);

        return asset($newPath);
    }
}
