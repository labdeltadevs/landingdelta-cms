<?php

use App\Models\HeroSlide;

test('the home page does not render the announcement modal without active slides', function () {
    HeroSlide::factory()->inactive()->create();

    $response = $this->get(route('public.home'));

    $response->assertOk();
    $response->assertDontSee('data-hero-slides-modal');
    $response->assertSee('Cuidando la salud de nuestra gente');
});

test('the home page renders the announcement modal with an active slide', function () {
    HeroSlide::factory()->create([
        'title' => 'Promoción especial',
        'subtitle' => '20% de descuento en todas las presentaciones.',
        'image_path' => 'hero/anuncio.jpg',
        'cta_label' => 'Ver oferta',
        'cta_url' => '/productos',
    ]);

    $response = $this->get(route('public.home'));

    $response->assertOk();
    $response->assertSee('data-hero-slides-modal');
    $response->assertSee('Promoción especial');
    $response->assertSee('/storage/hero/anuncio.jpg');
    $response->assertSee('Ver oferta');
});

test('inactive slides are not rendered in the announcement modal', function () {
    HeroSlide::factory()->create(['title' => 'Aviso activo']);
    HeroSlide::factory()->inactive()->create(['title' => 'Aviso inactivo']);

    $response = $this->get(route('public.home'));

    $response->assertOk();
    $response->assertSee('data-hero-slides-modal');
    $response->assertSee('Aviso activo');
    $response->assertDontSee('Aviso inactivo');
});

test('a slide without an image can be saved and rendered in the modal', function () {
    HeroSlide::factory()->create([
        'title' => 'Aviso sin imagen',
        'image_path' => null,
    ]);

    $response = $this->get(route('public.home'));

    $response->assertOk();
    $response->assertSee('data-hero-slides-modal');
    $response->assertSee('Aviso sin imagen');
});
