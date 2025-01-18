<?php

namespace App\Services\LocalFileService;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\Log;
use Exception;
use App\Services\LocalFileService\LocalFileServiceInterface;

class LocalFileService implements LocalFileServiceInterface {
    protected string $storageDisk;

    public function __construct(string $storageDisk = null) {
        $this->storageDisk = $storageDisk ?? config('filesystems.default', 'local');
    }

    /**
     * Upload a file (Supports UploadedFile and Local Files)
     */
    public function uploadFile(
        UploadedFile|string $file,
        string $directory,
        ?string $fileName = null
    ): array {
        try {
            $this->ensureDirectoryExists($directory);

            if ($file instanceof UploadedFile) {
                return $this->uploadUploadedFile($file, $directory, $fileName);
            } elseif (is_string($file) && file_exists($file)) {
                return $this->uploadLocalFile($file, $directory, $fileName);
            }

            throw new Exception('Invalid file type or file does not exist.');
        } catch (Exception $e) {
            Log::error('File upload failed: ' . $e->getMessage());
            throw new Exception('File upload failed');
        }
    }

    private function uploadUploadedFile(
        UploadedFile $file,
        string $directory,
        ?string $fileName = null
    ): array {
        $fileName = $fileName ?? $this->generateUniqueFileName($file);
        $filePath = Storage::disk($this->storageDisk)->putFileAs($directory, $file, $fileName);
        // $filePath = Storage::disk($this->storageDisk)->put($directory . '/' . $fileName, $file);

        return [
            'name' => $file->getClientOriginalName(),
            'path' => $filePath,
            'mime_type' => $file->getClientMimeType(),
            'size' => $file->getSize(),
        ];
    }

    private function uploadLocalFile(
        string $localFilePath,
        string $directory,
        ?string $fileName = null
    ): array {
        $fileName = $fileName ?? $this->generateUniqueFileName($localFilePath);
        $fileContents = file_get_contents($localFilePath);
        // $filePath = Storage::disk($this->storageDisk)->putFileAs($directory, $fileContents, $fileName);
        $filePath = Storage::disk($this->storageDisk)->put($directory . '/' . $fileName, $fileContents);

        return [
            'name' => basename($localFilePath),
            'path' => $filePath,
            'mime_type' => mime_content_type($localFilePath),
            'size' => filesize($localFilePath),
        ];
    }

    /**
     * Download a file
     */
    public function downloadFile(string $sourcePath, ?string $fileName = null): ?StreamedResponse {
        if (Storage::disk($this->storageDisk)->exists($sourcePath)) {
            return Storage::disk($this->storageDisk)->download($sourcePath, $fileName);
        }

        throw new Exception('File not found');
    }

    /**
     * Get file details
     */
    public function getFileDetails(string $sourcePath): ?array {
        if (Storage::disk($this->storageDisk)->exists($sourcePath)) {
            return [
                'path' => $sourcePath,
                'size' => Storage::disk($this->storageDisk)->size($sourcePath),
                'last_modified' => Storage::disk($this->storageDisk)->lastModified($sourcePath),
            ];
        }

        return null;
    }

    /**
     * Delete a file
     */
    public function deleteFile(string $sourcePath): bool {
        return Storage::disk($this->storageDisk)->delete($sourcePath);
    }

    /**
     * Move a file
     */
    public function moveFile(string $sourcePath, string $destinationPath): bool {
        if (Storage::disk($this->storageDisk)->exists($sourcePath)) {
            return Storage::disk($this->storageDisk)->move($sourcePath, $destinationPath);
        }

        throw new Exception('Source file does not exist.');
    }

    /**
     * Copy a file
     */
    public function copyFile(string $sourcePath, string $destinationPath): bool {
        if (Storage::disk($this->storageDisk)->exists($sourcePath)) {
            return Storage::disk($this->storageDisk)->copy($sourcePath, $destinationPath);
        }

        throw new Exception('Source file does not exist.');
    }

    /**
     * Rename a file
     */
    public function renameFile(string $sourcePath, string $newFileName): bool {
        if (Storage::disk($this->storageDisk)->exists($sourcePath)) {
            return Storage::disk($this->storageDisk)->move($sourcePath, dirname($sourcePath) . '/' . $newFileName);
        }

        throw new Exception('File does not exist.');
    }

    /**
     * Generate a unique file name for a file.
     */
    private function generateUniqueFileName(UploadedFile|string $file): string {
        if ($file instanceof UploadedFile) {
            return Str::uuid() . '.' . $file->getClientOriginalExtension();
        }
        return Str::uuid() . '.' . pathinfo($file, PATHINFO_EXTENSION);
    }

    /**
     * Ensure that the directory exists.
     */
    private function ensureDirectoryExists(string $directory): void {
        if (!Storage::disk($this->storageDisk)->exists($directory)) {
            Storage::disk($this->storageDisk)->makeDirectory($directory);
        }
    }
}
