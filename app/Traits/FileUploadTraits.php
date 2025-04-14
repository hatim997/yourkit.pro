<?php

namespace App\Traits;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

trait FileUploadTraits
{

    /**
     * Upload a file to the specified storage disk and directory.
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param string|null $disk
     * @return string|false
     */
    public function uploadFile(UploadedFile $file, string $directory, string $disk = 'public')
    {
        $filename = uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs($directory, $filename, $disk);

        return [
            'file_name' => $filename,
            'file_path' => $path
        ];
    }

    /**
     * Update a file by deleting the old one and uploading a new one.
     *
     * @param UploadedFile $file
     * @param string $oldFilePath
     * @param string $directory
     * @param string|null $disk
     * @return string|false
     */
    public function updateFile(UploadedFile $file, string $oldFilePath, string $directory, string $disk = 'public')
    {
        // Delete the old file
        if (Storage::disk($disk)->exists($oldFilePath)) {
            Storage::disk($disk)->delete($oldFilePath);
        }

        // Upload the new file
        return $this->uploadFile($file, $directory, $disk);
    }

    /**
     * Delete a file from the specified storage disk.
     *
     * @param string $filePath
     * @param string|null $disk
     * @return bool
     */
    public function deleteFile(string $filePath, string $disk = 'public')
    {
        if (Storage::disk($disk)->exists($filePath)) {
            return Storage::disk($disk)->delete($filePath);
        }

        return false;
    }
}
