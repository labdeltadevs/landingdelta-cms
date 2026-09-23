<?php

use App\Models\Product;

test('legacy pattern A redirige al slug canonico', function () {
    $product = Product::factory()->create(['slug' => 'eritromicina-500mg']);

    $response = $this->get('/productos/comprimidos-capsulas-sobres-tabletas/43-eritromicina-500-mg.html');

    $response->assertRedirect(route('public.products.show', $product));
    expect($response->getStatusCode())->toBe(301);
});

test('legacy pattern A tolera mayusculas y query utm', function () {
    Product::factory()->create(['slug' => 'calcio-500-d']);

    $response = $this->get('/productos/comprimidos-capsulas-sobres-tabletas/20-CALCIO-500-D.html?utm_source=qr');

    $response->assertRedirect(route('public.products.show', 'calcio-500-d'));
});

test('legacy pattern B redirige al slug canonico', function () {
    $product = Product::factory()->create(['slug' => 'acetilcisteina']);

    $response = $this->get('/producto/acetilcisteina/');

    $response->assertRedirect(route('public.products.show', $product));
    expect($response->getStatusCode())->toBe(301);
});

test('legacy miss redirige a productos sin 404', function () {
    $this->get('/producto/no-existe-xyz/')->assertRedirect(route('public.products.index'));
    $this->get('/productos/categoria/999-no-existe.html')->assertRedirect(route('public.products.index'));
});

test('la ruta canonica existente sigue respondiendo 200', function () {
    $product = Product::factory()->create();

    $this->get(route('public.products.show', $product))->assertOk();
});
