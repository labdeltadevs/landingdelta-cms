<?php

use App\Livewire\Admin\Products\ProductForm;
use App\Livewire\Admin\Products\ProductIndex;
use App\Models\Product;
use App\Models\User;
use App\Support\ProductCsv;
use App\Support\VademecumHtml;
use Database\Seeders\PermissionSeeder;
use Illuminate\Http\UploadedFile;
use Livewire\Livewire;

function actingAsAdmin(): void
{
    test()->seed(PermissionSeeder::class);
    test()->actingAs(User::factory()->withRole('admin')->create());
}

test('precarga las secciones estandar del vademecum en un producto nuevo', function () {
    actingAsAdmin();

    Livewire::test(ProductForm::class)
        ->assertCount('vademecumRows', 6)
        ->assertSet('vademecumRows.0.label', 'Presentación')
        ->assertSet('vademecumRows.1.label', 'Composición')
        ->assertSet('vademecumRows.2.label', 'Acción terapéutica')
        ->assertSet('vademecumRows.3.label', 'Posología')
        ->assertSet('vademecumRows.4.label', 'Indicaciones')
        ->assertSet('vademecumRows.5.label', 'Contraindicaciones')
        ->assertSet('vademecumRows.5.text', '');
});

test('crea un producto y guarda las filas del vademecum como tabla html', function () {
    actingAsAdmin();

    Livewire::test(ProductForm::class)
        ->set('name', 'GABAPENTINA 300 mg')
        ->set('active_ingredient', 'Gabapentina')
        ->set('vademecumRows', [
            ['id' => 'a', 'label' => 'Presentación', 'text' => 'Caja x 30 cápsulas'],
            ['id' => 'b', 'label' => 'Composición', 'text' => 'Gabapentina 300 mg/cápsula'],
            ['id' => 'c', 'label' => 'Posología', 'text' => "Dosis inicial: 300 mg/día\n3 dosis al día"],
            ['id' => 'd', 'label' => 'Sin contenido', 'text' => '   '],
        ])
        ->call('save')
        ->assertRedirect(route('admin.products.index'));

    $product = Product::where('name', 'GABAPENTINA 300 mg')->firstOrFail();

    expect($product->description)
        ->toContain('<table class="table-vademecum">')
        ->toContain('<tr><td>Presentación</td><td>Caja x 30 cápsulas</td></tr>')
        ->toContain('<tr><td>Posología</td><td>Dosis inicial: 300 mg/día<br>3 dosis al día</td></tr>')
        ->not->toContain('Sin contenido');
});

test('carga las filas existentes al editar un producto y guarda los cambios', function () {
    actingAsAdmin();

    $product = Product::factory()->create([
        'name' => 'ACETAZOLAMIDA 250 mg',
        'description' => VademecumHtml::build([
            ['label' => 'Presentación', 'text' => 'Blister x 10'],
            ['label' => 'Indicaciones', 'text' => 'Glaucoma'],
        ]),
    ]);

    Livewire::test(ProductForm::class, ['product' => $product])
        ->assertSet('vademecumRows.0.label', 'Presentación')
        ->assertSet('vademecumRows.1.text', 'Glaucoma')
        ->set('vademecumRows.0.text', 'Caja x 30 comprimidos')
        ->call('addVademecumRow')
        ->assertCount('vademecumRows', 3)
        ->set('vademecumRows.2.label', 'Contraindicaciones')
        ->set('vademecumRows.2.text', 'Hipersensibilidad')
        ->call('save')
        ->assertRedirect(route('admin.products.index'));

    $product->refresh();

    expect($product->description)
        ->toContain('<tr><td>Presentación</td><td>Caja x 30 comprimidos</td></tr>')
        ->toContain('<tr><td>Indicaciones</td><td>Glaucoma</td></tr>')
        ->toContain('<tr><td>Contraindicaciones</td><td>Hipersensibilidad</td></tr>');
});

test('quitar una fila la elimina del vademecum y reindexa el arreglo', function () {
    actingAsAdmin();

    Livewire::test(ProductForm::class)
        ->assertCount('vademecumRows', 6)
        ->call('removeVademecumRow', 0)
        ->assertCount('vademecumRows', 5)
        ->assertSet('vademecumRows.0.label', 'Composición');
});

test('carga una descripcion legada sin tabla como fila editable y la estandariza al guardar', function () {
    actingAsAdmin();

    $product = Product::factory()->create([
        'name' => 'LEGADO',
        'description' => 'Texto antiguo sin tabla',
    ]);

    Livewire::test(ProductForm::class, ['product' => $product])
        ->assertSet('vademecumRows.0.label', '')
        ->assertSet('vademecumRows.0.text', 'Texto antiguo sin tabla');

    Livewire::test(ProductForm::class, ['product' => $product])
        ->call('save')
        ->assertRedirect(route('admin.products.index'));

    $product->refresh();

    expect($product->description)
        ->toContain('<table class="table-vademecum">')
        ->toContain('Texto antiguo sin tabla');
});

test('el indice indica con un icono si el producto tiene imagen asignada', function () {
    actingAsAdmin();

    Product::factory()->create(['name' => 'Con Foto', 'main_image_path' => 'products/foto.jpg']);
    Product::factory()->create(['name' => 'Sin Foto', 'main_image_path' => null]);

    Livewire::test(ProductIndex::class)
        ->assertSee('Con Foto', false)
        ->assertSee('Sin Foto', false)
        ->assertSee('Con imagen:', false)
        ->assertSee('Sin imagen:', false)
        ->assertSee('text-emerald-500', false)
        ->assertSee('text-zinc-300', false);
});

test('genera el QR con la URL publica del producto', function () {
    actingAsAdmin();

    $product = Product::factory()->create(['internal_code' => 'PROD-000123']);

    Livewire::test(ProductIndex::class)
        ->call('showQr', $product->id)
        ->assertSet('qrUrl', route('public.products.show', $product))
        ->assertSet('qrName', $product->name)
        ->assertSet('barcodeValue', 'PROD-000123')
        ->assertSee('<svg', false);
});

test('exportar descarga el CSV con todos los productos', function () {
    actingAsAdmin();

    Product::factory()->create(['name' => 'Exportable Uno']);

    $component = Livewire::test(ProductIndex::class)->call('export');

    $component->assertFileDownloaded();

    $download = $component->effects['download'];

    expect($download['name'])->toStartWith('productos-')
        ->and($download['contentType'])->toBe('text/csv; charset=UTF-8');

    $csv = base64_decode($download['content']);

    expect($csv)->toContain('id;name;internal_code;slug;active_ingredient;brand_id;category_id;is_active;is_featured')
        ->and($csv)->toContain('Exportable Uno');
});

test('el mapeo de exportacion usa las 9 columnas pedidas', function () {
    $product = Product::factory()->create([
        'name' => 'Aspirina 500',
        'internal_code' => 'PROD-XYZ',
        'active_ingredient' => 'Ácido acetilsalicílico',
        'is_active' => true,
        'is_featured' => false,
    ]);

    expect(ProductCsv::row($product))->toBe([
        $product->id,
        'Aspirina 500',
        'PROD-XYZ',
        $product->slug,
        'Ácido acetilsalicílico',
        $product->brand_id,
        $product->category_id,
        '1',
        '0',
    ]);
});

test('el parseo tolera BOM, punto y coma y detecta columnas faltantes', function () {
    $csv = "\xEF\xBB\xBFid;name;internal_code;slug;active_ingredient;brand_id;category_id;is_active;is_featured\n1;Aspirina;PROD-1;asp;Acido;2;3;1;0\n";

    $parsed = ProductCsv::parse($csv);

    expect($parsed['missing'])->toBe([])
        ->and($parsed['rows'])->toHaveCount(1)
        ->and($parsed['rows'][0]['name'])->toBe('Aspirina')
        ->and($parsed['rows'][0]['is_active'])->toBe('1');

    $comma = "id,name\n1,Aspirina\n";
    expect(ProductCsv::parse($comma)['delimiter'])->toBe(',')
        ->and(ProductCsv::parse($comma)['missing'])->toContain('internal_code');
});

test('normaliza booleanos en español e ids', function () {
    expect(ProductCsv::normalizeBool('sí'))->toBeTrue()
        ->and(ProductCsv::normalizeBool('SÍ'))->toBeTrue()
        ->and(ProductCsv::normalizeBool('0'))->toBeFalse()
        ->and(ProductCsv::normalizeBool('no'))->toBeFalse()
        ->and(ProductCsv::normalizeBool('true'))->toBeTrue()
        ->and(ProductCsv::normalizeId('12'))->toBe(12)
        ->and(ProductCsv::normalizeId(''))->toBeNull()
        ->and(ProductCsv::normalizeId('abc'))->toBeNull();
});

test('importar actualiza por id y aplica el slug si cambia', function () {
    actingAsAdmin();

    $product = Product::factory()->create(['name' => 'Viejo', 'slug' => 'viejo-1']);

    $csv = "id;name;internal_code;slug;active_ingredient;brand_id;category_id;is_active;is_featured\n{$product->id};Nuevo;PROD-9;nuevo-slug;Ibuprofeno;;;sí;0\n";

    Livewire::test(ProductIndex::class)
        ->set('importFile', UploadedFile::fake()->createWithContent('productos.csv', $csv))
        ->call('import')
        ->assertSet('importResult.created', 0)
        ->assertSet('importResult.updated', 1)
        ->assertSet('importResult.skipped', 0);

    $product->refresh();

    expect($product->name)->toBe('Nuevo')
        ->and($product->slug)->toBe('nuevo-slug')
        ->and($product->active_ingredient)->toBe('Ibuprofeno')
        ->and($product->is_active)->toBeTrue();
});

test('importar conserva el slug si viene vacío o igual', function () {
    actingAsAdmin();

    $product = Product::factory()->create(['name' => 'Viejo', 'slug' => 'viejo-1']);

    $csvVacio = "id;name;internal_code;slug;active_ingredient;brand_id;category_id;is_active;is_featured\n{$product->id};Nuevo2;;;Ibuprofeno;;;1;0\n";
    $csvIgual = "id;name;internal_code;slug;active_ingredient;brand_id;category_id;is_active;is_featured\n{$product->id};Nuevo3;PROD-9;viejo-1;Ibuprofeno;;;1;0\n";

    Livewire::test(ProductIndex::class)
        ->set('importFile', UploadedFile::fake()->createWithContent('productos.csv', $csvVacio))
        ->call('import')
        ->assertSet('importResult.updated', 1);

    expect($product->refresh()->slug)->toBe('viejo-1');

    Livewire::test(ProductIndex::class)
        ->set('importFile', UploadedFile::fake()->createWithContent('productos.csv', $csvIgual))
        ->call('import')
        ->assertSet('importResult.updated', 1);

    expect($product->refresh()->slug)->toBe('viejo-1');
});

test('importar reporta skip si el slug ya existe', function () {
    actingAsAdmin();

    $a = Product::factory()->create(['slug' => 'slug-a']);
    $b = Product::factory()->create(['slug' => 'slug-b']);

    $csv = "id;name;internal_code;slug;active_ingredient;brand_id;category_id;is_active;is_featured\n{$b->id};B;PROD-9;slug-a;;;;1;0\n";

    Livewire::test(ProductIndex::class)
        ->set('importFile', UploadedFile::fake()->createWithContent('productos.csv', $csv))
        ->call('import')
        ->assertSet('importResult.skipped', 1)
        ->assertSet('importResult.updated', 0);

    expect($b->refresh()->slug)->toBe('slug-b');
});

test('importar crea el producto si el id no existe y omite filas sin nombre', function () {
    actingAsAdmin();

    $csv = "id;name;internal_code;slug;active_ingredient;brand_id;category_id;is_active;is_featured\n99999;Creado;;;Paracetamol;999;999;1;0\n;;;;;;;;\n";

    Livewire::test(ProductIndex::class)
        ->set('importFile', UploadedFile::fake()->createWithContent('productos.csv', $csv))
        ->call('import')
        ->assertSet('importResult.created', 1)
        ->assertSet('importResult.updated', 0)
        ->assertSet('importResult.skipped', 1);

    $created = Product::where('name', 'Creado')->firstOrFail();

    expect($created->brand_id)->toBeNull()
        ->and($created->category_id)->toBeNull()
        ->and($created->internal_code)->not->toBeNull()
        ->and($created->slug)->not->toBeNull();
});
