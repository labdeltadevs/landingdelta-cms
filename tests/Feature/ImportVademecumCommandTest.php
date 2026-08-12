<?php

use App\Models\Product;

function makeVademecumFixture(): array
{
    return [
        'vademecum' => base_path('tests/Fixtures/vademecum-test.csv'),
        'mapping' => base_path('tests/Fixtures/vademecum-mapping-test.csv'),
    ];
}

function createVademecumProducts(): void
{
    Product::factory()->create(['name' => 'AMBROXOL 15 mg/5 ml Jarabe', 'description' => 'descripcion vieja']);
    Product::factory()->create(['name' => 'METOCLOPRAMIDA 5 mg/ml Gotas', 'description' => 'meto vieja']);
    Product::factory()->create(['name' => 'MERONA 500 mg', 'description' => 'merona vieja']);
    Product::factory()->create(['name' => 'MERONA FORTE 1000 mg', 'description' => 'forte vieja']);
    Product::factory()->create(['name' => 'AMOXICILINA 500 mg', 'description' => 'amoxi vieja']);
    Product::factory()->create(['name' => 'AMOXICILINA 500 mg', 'description' => 'amoxi vieja']);
    Product::factory()->create(['name' => 'VITAMINA C 100 mg', 'description' => 'vitamina vieja']);
    Product::factory()->create(['name' => 'OTRO PRODUCTO', 'description' => 'intacto']);
}

it('actualiza la descripcion de los productos mapeados sin tocar otros campos', function () {
    createVademecumProducts();
    $fixture = makeVademecumFixture();

    $this->artisan('products:vademecum', ['--vademecum' => $fixture['vademecum'], '--mapping' => $fixture['mapping']])
        ->expectsOutputToContain('Importación completada: 5 productos actualizados')
        ->expectsOutputToContain('Vademécum sin datos')
        ->expectsOutputToContain('Producto(s) destino sin resolver')
        ->assertSuccessful();

    $ambroxol = Product::where('name', 'AMBROXOL 15 mg/5 ml Jarabe')->first();
    expect($ambroxol->description)->toContain('<table class="table-vademecum">')
        ->toContain('Presentación')
        ->toContain('Jarabe de 120 ml')
        ->toContain('Expectorante')
        ->toContain('Hipersecreción bronquial')
        ->toContain('Posología');

    $metoclopramida = Product::where('name', 'METOCLOPRAMIDA 5 mg/ml Gotas')->first();
    expect($metoclopramida->description)->toBe('meto vieja');

    $vitamina = Product::where('name', 'VITAMINA C 100 mg')->first();
    expect($vitamina->description)->toBe('vitamina vieja');

    $otros = Product::where('name', 'OTRO PRODUCTO')->first();
    expect($otros->description)->toBe('intacto');

    $amoxi = Product::where('name', 'AMOXICILINA 500 mg')->orderBy('id')->get();

    expect($amoxi)->toHaveCount(2);

    foreach ($amoxi as $product) {
        expect($product->description)
            ->toContain('Caja con 24 cápsulas')
            ->toContain('Caja con 24 comprimidos');
    }
});

it('fusiona varias filas del vademecum para un mismo from', function () {
    createVademecumProducts();
    $fixture = makeVademecumFixture();

    $this->artisan('products:vademecum', ['--vademecum' => $fixture['vademecum'], '--mapping' => $fixture['mapping']])
        ->assertSuccessful();

    $merona = Product::where('name', 'MERONA 500 mg')->first();
    $forte = Product::where('name', 'MERONA FORTE 1000 mg')->first();

    expect($merona->description)->toContain('Meropenem 500 mg')->toContain('Meropenem 1000 mg');
    expect($forte->description)->toContain('Meropenem 500 mg')->toContain('Meropenem 1000 mg');
});

it('no escribe nada en modo dry-run', function () {
    createVademecumProducts();
    $fixture = makeVademecumFixture();

    $this->artisan('products:vademecum', ['--dry-run' => true, '--vademecum' => $fixture['vademecum'], '--mapping' => $fixture['mapping']])
        ->expectsOutputToContain('No se escribió nada en la base de datos')
        ->expectsOutputToContain('[dry-run]')
        ->assertSuccessful();

    expect(Product::pluck('description')->all())
        ->toContain('descripcion vieja', 'meto vieja', 'intacto');
});
