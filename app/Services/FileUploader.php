<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploader
{
    /**
     * Guarda un archivo subido. Si se indica $entityType y es una imagen,
     * se optimiza con ImageOptimizer; si no, se guarda tal cual (ej: PDF).
     */
    public function upload(UploadedFile $file, string $directory = 'uploads', string $disk = 'public', ?string $entityType = null): string
    {
        if ($entityType !== null && str_starts_with((string) $file->getMimeType(), 'image/')) {
            $preset = config("image-optimizer.entities.{$entityType}", []);

            return app(ImageOptimizer::class)->optimizeAndStore(
                $file->getRealPath(),
                $directory,
                $preset['maxWidth'] ?? null,
                $preset['quality'] ?? null,
                $preset['format'] ?? null,
            );
        }

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
