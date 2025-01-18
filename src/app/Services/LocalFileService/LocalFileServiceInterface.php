<?php

namespace App\Services\LocalFileService;

use Illuminate\Http\UploadedFile;
use Symfony\Component\HttpFoundation\StreamedResponse;

interface LocalFileServiceInterface {
    public function uploadFile(UploadedFile|string $file, string $directory, ?string $fileName = null): array;
    public function downloadFile(string $sourcePath, ?string $fileName = null): ?StreamedResponse;
    public function getFileDetails(string $sourcePath): ?array;
    public function deleteFile(string $sourcePath): bool;
    public function moveFile(string $sourcePath, string $destinationPath): bool;
    public function copyFile(string $sourcePath, string $destinationPath): bool;
    public function renameFile(string $sourcePath, string $newFileName): bool;
}
