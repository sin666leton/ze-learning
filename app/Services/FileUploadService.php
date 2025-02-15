<?php
namespace App\Services;

use Illuminate\Support\Facades\Storage;

class FileUploadService
{
    public function encryptFileName(string $filename, string $ext)
    {
        return md5($filename.now()).".$ext";
    }

    public function uploadAnswerAssignment($file, string $name)
    {
        return Storage::disk('public')->putFileAs(
            '/archive/assignment',
            $file,
            $name
        );
    }

    public function fileExists(string $path)
    {
        return Storage::disk('public')->exists($path);
    }

    public function getFilePath($file)
    {
        return Storage::url($file);
    }
}