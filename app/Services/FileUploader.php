<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploader
{
    public function upload(UploadedFile $file, string $directory = 'uploads', string $disk = 'public'): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = Str::uuid().'.'.$extension;

        return $file->storeAs($directory, $filename, $disk);
    }

    public function delete(?string $path, string $disk = 'public'): void
    {
        if ($path && Storage::disk($disk)->exists($path)) {
            Storage::disk($disk)->delete($path);
        }
    }
}
