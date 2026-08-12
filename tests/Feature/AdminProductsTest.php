<?php

use App\Livewire\Admin\Products\ProductForm;
use App\Models\Product;
use App\Models\User;
use App\Support\VademecumHtml;
use Database\Seeders\PermissionSeeder;
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
