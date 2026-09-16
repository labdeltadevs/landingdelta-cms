<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\News;
use App\Models\Product;

function extractJsonLd(string $html, ?string $type = null): ?array
{
    if (! preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $matches)) {
        return null;
    }

    foreach ($matches[1] as $block) {
        $decoded = json_decode(trim($block), true);

        if (! is_array($decoded)) {
            continue;
        }

        if ($type === null || ($decoded['@type'] ?? null) === $type) {
            return $decoded;
        }
    }

    return null;
}

test('news article json-ld has valid dates and organization urls', function () {
    $news = News::factory()->create([
        'title' => 'Noticia de prueba',
        'published_at' => now()->subDay(),
    ]);

    $response = $this->get(route('public.news.show', $news));

    $response->assertOk();

    $jsonLd = extractJsonLd($response->getContent(), 'NewsArticle');

    expect($jsonLd)->not->toBeNull()
        ->and($jsonLd['@type'])->toBe('NewsArticle')
        ->and($jsonLd['headline'])->toBe('Noticia de prueba');

    // datePublished debe ser ISO 8601 válido
    expect($jsonLd['datePublished'] ?? null)->not->toBeNull();
    expect(fn () => new DateTime($jsonLd['datePublished']))->not->toThrow(Exception::class);

    // dateModified debe ser ISO 8601 válido si existe
    if (isset($jsonLd['dateModified'])) {
        expect(fn () => new DateTime($jsonLd['dateModified']))->not->toThrow(Exception::class);
    }

    // author y publisher deben tener url
    expect($jsonLd['author']['@type'])->toBe('Organization')
        ->and($jsonLd['author']['url'] ?? null)->not->toBeNull()
        ->and($jsonLd['publisher']['@type'])->toBe('Organization')
        ->and($jsonLd['publisher']['url'] ?? null)->not->toBeNull()
        ->and($jsonLd['publisher']['logo']['@type'] ?? null)->toBe('ImageObject');
});

test('news article json-ld omits dates when null', function () {
    $news = News::factory()->create([
        'title' => 'Noticia sin fechas',
        'published_at' => now()->subDay(),
    ]);
    // Forzar updated_at a null para simular el caso problemático
    News::query()->whereKey($news->id)->update(['updated_at' => null]);

    $response = $this->get(route('public.news.show', $news));

    $response->assertOk();

    $jsonLd = extractJsonLd($response->getContent(), 'NewsArticle');

    expect($jsonLd)->not->toBeNull()
        ->and($jsonLd)->not->toHaveKey('dateModified');
});

test('product json-ld has required offer fields with correct types', function () {
    $brand = Brand::factory()->create(['name' => 'Marca Test']);
    $category = Category::factory()->create(['name' => 'Categoría Test']);
    $product = Product::factory()->create([
        'name' => 'Producto Test',
        'active_ingredient' => 'Paracetamol',
        'approx_price' => 25.50,
        'brand_id' => $brand->id,
        'category_id' => $category->id,
        'main_image_path' => 'products/test.jpg',
    ]);

    $response = $this->get(route('public.products.show', $product));

    $response->assertOk();

    $jsonLd = extractJsonLd($response->getContent(), 'Product');

    expect($jsonLd)->not->toBeNull()
        ->and($jsonLd['@type'])->toBe('Product')
        ->and($jsonLd['name'])->toBe('Producto Test');

    // offers.price y priceCurrency son OBLIGATORIOS
    expect($jsonLd['offers']['@type'] ?? null)->toBe('Offer')
        ->and($jsonLd['offers']['price'] ?? null)->not->toBeNull()
        ->and($jsonLd['offers']['priceCurrency'] ?? null)->toBe('BOB')
        ->and($jsonLd['offers']['availability'] ?? null)->toBe('https://schema.org/InStock');

    // price debe ser numérico (string numérico es aceptable en JSON-LD)
    expect(is_numeric($jsonLd['offers']['price']))->toBeTrue();

    // brand debe tener url
    expect($jsonLd['brand']['@type'] ?? null)->toBe('Brand')
        ->and($jsonLd['brand']['url'] ?? null)->not->toBeNull();

    // seller debe tener url
    expect($jsonLd['offers']['seller']['url'] ?? null)->not->toBeNull();

    // image debe estar presente cuando hay imagen
    expect($jsonLd['image'] ?? null)->toContain('products/test.jpg');

    // additionalProperty debe contener principio activo y categoría
    $props = collect($jsonLd['additionalProperty'] ?? []);
    expect($props->where('name', 'Principio activo')->first()['value'] ?? null)->toBe('Paracetamol')
        ->and($props->where('name', 'Categoría')->first()['value'] ?? null)->toBe('Categoría Test');

    // NO debe tener activeIngredient ni category como propiedades directas
    expect($jsonLd)->not->toHaveKey('activeIngredient')
        ->and($jsonLd)->not->toHaveKey('category');
});

test('product json-ld omits image when no image exists', function () {
    $product = Product::factory()->create([
        'name' => 'Producto Sin Imagen',
        'main_image_path' => null,
    ]);

    $response = $this->get(route('public.products.show', $product));

    $response->assertOk();

    $jsonLd = extractJsonLd($response->getContent(), 'Product');

    expect($jsonLd)->not->toBeNull()
        ->and($jsonLd)->not->toHaveKey('image');
});

test('product json-ld handles missing optional relations', function () {
    $product = Product::factory()->create([
        'name' => 'Producto Mínimo',
        'active_ingredient' => null,
        'approx_price' => null,
        'brand_id' => null,
        'category_id' => null,
    ]);

    $response = $this->get(route('public.products.show', $product));

    $response->assertOk();

    $jsonLd = extractJsonLd($response->getContent(), 'Product');

    expect($jsonLd)->not->toBeNull()
        ->and($jsonLd['offers']['price'] ?? null)->not->toBeNull()
        ->and($jsonLd['brand']['name'] ?? null)->toBe('Laboratorios Delta')
        ->and($jsonLd['additionalProperty'] ?? null)->toBeArray();
});
