<?php

use App\Models\Product;

it('imports products from the WooCommerce CSV', function () {
    Product::create(['internal_code' => 'OLD_1', 'name' => 'Viejo', 'slug' => 'viejo']);

    $fixture = base_path('tests/Fixtures/wc-product-export-test.csv');

    $this->artisan('products:import', ['--path' => $fixture])
        ->expectsOutputToContain('Productos creados')
        ->assertSuccessful();

    expect(Product::count())->toBe(3);

    $acetazolamida = Product::where('internal_code', 'PPROD_1696')->first();

    expect($acetazolamida)->not->toBeNull()
        ->and($acetazolamida->name)->toBe('ACETAZOLAMIDA 250 mg')
        ->and($acetazolamida->slug)->toBe('acetazolamida-250-mg')
        ->and($acetazolamida->sort)->toBe(1696)
        ->and($acetazolamida->is_active)->toBeTrue()
        ->and($acetazolamida->description)->toContain('<h4 class="subtitulo2">Tratamiento del glaucoma</h4>')
        ->and($acetazolamida->description)->toContain("\n")
        ->and($acetazolamida->brand->name)->toBe('Delta')
        ->and($acetazolamida->category->name)->toBe('Comprimidos, Cápsulas, Sobres, Tabletas');
});

it('strips trailing brand labels from category names', function () {
    $fixture = base_path('tests/Fixtures/wc-product-export-test.csv');

    $this->artisan('products:import', ['--path' => $fixture])->assertSuccessful();

    $product = Product::where('internal_code', 'PPROD_3028')->first();

    expect($product->brand->name)->toBe('Synthera')
        ->and($product->category->name)->toBe('LS Inyectables');
});

it('creates products without description as null', function () {
    $fixture = base_path('tests/Fixtures/wc-product-export-test.csv');

    $this->artisan('products:import', ['--path' => $fixture])->assertSuccessful();

    $product = Product::where('internal_code', 'PPROD_2410')->first();

    expect($product->description)->toBeNull();
});

it('replaces the products table on each run', function () {
    $fixture = base_path('tests/Fixtures/wc-product-export-test.csv');

    $this->artisan('products:import', ['--path' => $fixture])->assertSuccessful();
    $this->artisan('products:import', ['--path' => $fixture])->assertSuccessful();

    expect(Product::count())->toBe(3);
});

it('fails when the CSV file is missing', function () {
    $this->artisan('products:import', ['--path' => 'missing.csv'])->assertFailed();
});
