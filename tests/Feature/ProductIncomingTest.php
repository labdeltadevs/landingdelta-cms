<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('public');
    Storage::disk('public')->put('images/products/proximamente.svg', '<svg></svg>');
    Storage::disk('public')->put('products/test.jpg', 'fake image');
    Storage::disk('public')->put('logo_delta.png', 'fake logo');
});

test('el buscador del catalogo ignora mayusculas y minusculas', function () {
    Product::factory()->create([
        'name' => 'ACETAZOLAMIDA 250 mg',
        'slug' => 'acetazolamida-250-mg',
        'is_active' => true,
    ]);

    $response = $this->get(route('public.products.index', ['q' => 'acetazolamida']));

    $response->assertOk()->assertSee('ACETAZOLAMIDA 250 mg');
});

test('el buscador del catalogo ignora mayusculas en el principio activo', function () {
    Product::factory()->create([
        'name' => 'Producto X',
        'slug' => 'producto-x',
        'active_ingredient' => 'PARACETAMOL 500 mg',
        'is_active' => true,
    ]);

    $response = $this->get(route('public.products.index', ['q' => 'paracetamol']));

    $response->assertOk()->assertSee('Producto X');
});

test('un producto proximo lanzamiento renderiza el svg y el badge en el catalogo', function () {
    $category = Category::factory()->create();
    $brand = Brand::factory()->create();

    Product::factory()->create([
        'name' => 'PRODUCTO PROXIMO',
        'slug' => 'producto-proximo',
        'main_image_path' => 'products/test.jpg',
        'category_id' => $category->id,
        'brand_id' => $brand->id,
        'is_active' => true,
        'is_incoming' => true,
    ]);

    $response = $this->get(route('public.products.index'));

    $response->assertOk()
        ->assertSee('PRODUCTO PROXIMO')
        ->assertSee('images/products/proximamente.svg')
        ->assertSee('Próximamente');
});

test('un producto proximo lanzamiento sin imagen muestra el svg en la ficha', function () {
    Product::factory()->create([
        'name' => 'PRODUCTO PROXIMO SIN IMG',
        'slug' => 'producto-proximo-sin-img',
        'main_image_path' => null,
        'is_active' => true,
        'is_incoming' => true,
    ]);

    $response = $this->get(route('public.products.show', 'producto-proximo-sin-img'));

    $response->assertOk()
        ->assertSee('images/products/proximamente.svg')
        ->assertSee('Próximamente');
});

test('un producto normal conserva su imagen real en la ficha', function () {
    Product::factory()->create([
        'name' => 'PRODUCTO NORMAL',
        'slug' => 'producto-normal',
        'main_image_path' => 'products/test.jpg',
        'is_active' => true,
        'is_incoming' => false,
    ]);

    $response = $this->get(route('public.products.show', 'producto-normal'));

    $response->assertOk()
        ->assertSee('products/test.jpg')
        ->assertDontSee('images/products/proximamente.svg');
});

test('el filtro de proximos lanzamientos en el catalogo funciona', function () {
    Product::factory()->create([
        'name' => 'LANZAMIENTO 1',
        'slug' => 'lanzamiento-1',
        'is_active' => true,
        'is_incoming' => true,
    ]);
    Product::factory()->create([
        'name' => 'LANZAMIENTO 2',
        'slug' => 'lanzamiento-2',
        'is_active' => true,
        'is_incoming' => true,
    ]);
    Product::factory()->create([
        'name' => 'PRODUCTO ACTUAL',
        'slug' => 'producto-actual',
        'is_active' => true,
        'is_incoming' => false,
    ]);

    $response = $this->get(route('public.products.index', ['incoming' => '1']));

    $response->assertOk()
        ->assertSee('LANZAMIENTO 1')
        ->assertSee('LANZAMIENTO 2')
        ->assertDontSee('PRODUCTO ACTUAL');
});

test('el json-ld de un producto proximo declara PreOrder y usa el logo como imagen', function () {
    Product::factory()->create([
        'name' => 'PRODUCTO PREORDER',
        'slug' => 'producto-preorder',
        'main_image_path' => 'products/test.jpg',
        'is_active' => true,
        'is_incoming' => true,
    ]);

    $response = $this->get(route('public.products.show', 'producto-preorder'));

    $response->assertOk();

    $content = $response->getContent();

    expect($content)->toContain('"@type":"Product"')
        ->and($content)->toContain('"availability":"https://schema.org/PreOrder"')
        ->and($content)->toContain('logo_delta.png');
});
