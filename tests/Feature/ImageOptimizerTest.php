<?php

use App\Services\ImageOptimizer;
use Illuminate\Support\Facades\Storage;

function makeTestJpeg(int $width = 2000, int $height = 1000): string
{
    $image = imagecreatetruecolor($width, $height);
    imagefill($image, 0, 0, imagecolorallocate($image, 200, 100, 50));
    $path = sys_get_temp_dir().'/optimizer-test-'.uniqid().'.jpg';
    imagejpeg($image, $path, 90);
    imagedestroy($image);

    return $path;
}

test('optimiza un producto a WebP de 1200px', function () {
    Storage::fake('public');

    $path = app(ImageOptimizer::class)->optimizeAndStore(makeTestJpeg(), 'products', 1200, 80, 'webp');

    expect($path)->toStartWith('products/')
        ->and(pathinfo($path, PATHINFO_EXTENSION))->toBe('webp')
        ->and(Storage::disk('public')->exists($path))->toBeTrue();

    $size = getimagesize(Storage::disk('public')->path($path));

    expect($size[0])->toBe(1200)
        ->and($size['mime'])->toBe('image/webp');
});

test('usa presets por entidad', function () {
    Storage::fake('public');

    $path = app(ImageOptimizer::class)->optimizeForEntity(makeTestJpeg(), 'brand');

    expect($path)->toStartWith('brands/');

    $size = getimagesize(Storage::disk('public')->path($path));

    expect($size[0])->toBe(800);
});

test('valida extensiones y respeta el formato original', function () {
    Storage::fake('public');

    $optimizer = app(ImageOptimizer::class);

    expect($optimizer->allowsExtension('jpg'))->toBeTrue()
        ->and($optimizer->allowsExtension('webp'))->toBeTrue()
        ->and($optimizer->allowsExtension('pdf'))->toBeFalse()
        ->and($optimizer->allowsExtension('svg'))->toBeFalse();

    $path = $optimizer->optimizeAndStore(makeTestJpeg(), 'products', 1200, 80, 'original');

    expect(pathinfo($path, PATHINFO_EXTENSION))->toBe('jpg');
});

test('optimizeLivewireTempFile guarda y limpia el temporal', function () {
    Storage::fake('public');
    Storage::fake('local');

    Storage::disk('local')->put('livewire-tmp/src.jpg', file_get_contents(makeTestJpeg()));
    Storage::disk('local')->put('livewire-tmp/src.jpg.json', '{}');

    $path = app(ImageOptimizer::class)->optimizeLivewireTempFile('local', 'livewire-tmp/src.jpg', 'product');

    expect(Storage::disk('public')->exists($path))->toBeTrue()
        ->and(Storage::disk('local')->exists('livewire-tmp/src.jpg'))->toBeFalse()
        ->and(Storage::disk('local')->exists('livewire-tmp/src.jpg.json'))->toBeFalse();
});

test('deleteIfExists borra solo si existe', function () {
    Storage::fake('public');

    Storage::disk('public')->put('products/ghost.webp', 'data');

    app(ImageOptimizer::class)->deleteIfExists('products/ghost.webp');
    app(ImageOptimizer::class)->deleteIfExists('products/missing.webp');
    app(ImageOptimizer::class)->deleteIfExists(null);

    expect(Storage::disk('public')->exists('products/ghost.webp'))->toBeFalse();
});
