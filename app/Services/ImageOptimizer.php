<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Encoders\AvifEncoder;
use Intervention\Image\Encoders\JpegEncoder;
use Intervention\Image\Encoders\PngEncoder;
use Intervention\Image\Encoders\WebpEncoder;
use Intervention\Image\ImageManager;

/**
 * Optimiza imágenes subidas (redimensiona + convierte a WebP)
 * y las guarda en el disco público.
 */
class ImageOptimizer
{
    protected ImageManager $manager;

    public function __construct(
        protected int $maxWidth = 1920,
        protected int $quality = 80,
        protected string $format = 'webp',
    ) {
        $this->manager = new ImageManager(new Driver);
    }

    /**
     * Optimiza un archivo temporal y lo guarda en el disco público.
     *
     * @return string Ruta relativa guardada (ej: products/uuid.webp).
     */
    public function optimizeAndStore(
        string $tempPath,
        string $targetDir,
        ?int $maxWidth = null,
        ?int $quality = null,
        ?string $format = null,
    ): string {
        $maxWidth = $maxWidth ?? $this->maxWidth;
        $quality = $quality ?? $this->quality;
        $format = strtolower($format ?? $this->format);

        $image = $this->manager->decodePath($tempPath);

        if ($image->width() > $maxWidth) {
            $image->scaleDown(width: $maxWidth);
        }

        if ($format === 'original') {
            $extension = strtolower(pathinfo($tempPath, PATHINFO_EXTENSION)) ?: 'jpg';
            $encoded = match ($extension) {
                'png' => $image->encode(new PngEncoder),
                'webp' => $image->encode(new WebpEncoder(quality: $quality)),
                'avif' => $image->encode(new AvifEncoder(quality: $quality)),
                default => $image->encode(new JpegEncoder(quality: $quality)),
            };
        } else {
            $extension = $format;
            $encoded = match ($format) {
                'png' => $image->encode(new PngEncoder),
                'avif' => $image->encode(new AvifEncoder(quality: $quality)),
                'jpg', 'jpeg' => $image->encode(new JpegEncoder(quality: $quality)),
                default => $image->encode(new WebpEncoder(quality: $quality)),
            };
            $extension = $format === 'jpeg' ? 'jpg' : $format;
        }

        $path = trim($targetDir, '/').'/'.Str::uuid().'.'.$extension;

        Storage::disk('public')->put($path, (string) $encoded);

        return $path;
    }

    /**
     * Optimiza según presets por tipo de entidad.
     */
    public function optimizeForEntity(string $tempPath, string $entityType): string
    {
        $config = $this->entityConfig($entityType);

        return $this->optimizeAndStore(
            $tempPath,
            $config['dir'],
            $config['maxWidth'],
            $config['quality'],
            $config['format'],
        );
    }

    /**
     * @return array{dir: string, maxWidth: int, quality: int, format: string}
     */
    protected function entityConfig(string $type): array
    {
        $entities = config('image-optimizer.entities', []);

        return $entities[$type] ?? [
            'dir' => 'misc',
            'maxWidth' => $this->maxWidth,
            'quality' => $this->quality,
            'format' => $this->format,
        ];
    }

    public function deleteIfExists(?string $path): void
    {
        if ($path && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * Optimiza un temporal de Livewire (disco local) y limpia el temporal.
     *
     * @return string Ruta relativa guardada en el disco público.
     */
    public function optimizeLivewireTempFile(string $disk, string $storagePath, string $entityType): string
    {
        try {
            return $this->optimizeForEntity(Storage::disk($disk)->path($storagePath), $entityType);
        } finally {
            Storage::disk($disk)->delete($storagePath);
            Storage::disk($disk)->delete($storagePath.'.json');
        }
    }

    public function allowsExtension(string $extension): bool
    {
        return in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'webp'], true);
    }
}
