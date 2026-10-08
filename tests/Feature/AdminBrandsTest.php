<?php

use App\Livewire\Admin\Brands\BrandIndex;
use App\Models\Brand;
use App\Models\User;
use App\Support\BarcodeGenerator;
use App\Support\QrCodeGenerator;
use Database\Seeders\PermissionSeeder;
use Livewire\Livewire;

test('el indice indica con un icono si la marca tiene imagen asignada', function () {
    $this->seed(PermissionSeeder::class);
    $this->actingAs(User::factory()->withRole('admin')->create());

    Brand::factory()->create(['name' => 'Con Logo', 'logo_path' => 'brands/logo.png']);
    Brand::factory()->create(['name' => 'Sin Logo', 'logo_path' => null]);

    Livewire::test(BrandIndex::class)
        ->assertSee('Con Logo', false)
        ->assertSee('Sin Logo', false)
        ->assertSee('Con imagen:', false)
        ->assertSee('Sin imagen:', false)
        ->assertSee('text-emerald-500', false)
        ->assertSee('text-zinc-300', false);
});

test('el generador produce un SVG valido para una URL', function () {
    $svg = app(QrCodeGenerator::class)->svg('https://ejemplo.bo/productos/demo');

    expect($svg)->toContain('<svg')->toContain('</svg>');
});

test('el generador de barras produce un SVG valido para un codigo', function () {
    $svg = app(BarcodeGenerator::class)->svg('PROD-000123');

    expect($svg)->toContain('<svg')->toContain('</svg>');
});

test('genera el QR con la URL publica de la marca', function () {
    $this->seed(PermissionSeeder::class);
    $this->actingAs(User::factory()->withRole('admin')->create());

    $brand = Brand::factory()->create();

    Livewire::test(BrandIndex::class)
        ->call('showQr', $brand->id)
        ->assertSet('qrUrl', route('public.brands.show', $brand))
        ->assertSet('qrName', $brand->name)
        ->assertSet('codeFilename', 'codigo-division-'.$brand->slug)
        ->assertSet('barcodeSvg', null)
        ->assertSet('barcodeValue', null)
        ->assertSee('<svg', false);
});

test('genera el codigo de barras de la marca sin QR', function () {
    $this->seed(PermissionSeeder::class);
    $this->actingAs(User::factory()->withRole('admin')->create());

    $brand = Brand::factory()->create();

    Livewire::test(BrandIndex::class)
        ->call('showBarcode', $brand->id)
        ->assertSet('barcodeValue', $brand->slug)
        ->assertSet('codeFilename', 'codigo-division-'.$brand->slug)
        ->assertSet('qrSvg', null)
        ->assertSet('qrUrl', null)
        ->assertSee('<svg', false);
});
