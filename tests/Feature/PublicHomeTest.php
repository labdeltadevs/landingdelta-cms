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

test('slides outside the validity range are not rendered in the modal', function () {
    HeroSlide::factory()->expired()->create(['title' => 'Aviso vencido']);
    HeroSlide::factory()->upcoming()->create(['title' => 'Aviso futuro']);

    $response = $this->get(route('public.home'));

    $response->assertOk();
    $response->assertDontSee('data-hero-slides-modal');
    $response->assertDontSee('Aviso vencido');
    $response->assertDontSee('Aviso futuro');
});

test('only slides inside the validity range are rendered in the modal', function () {
    HeroSlide::factory()->current()->create(['title' => 'Aviso vigente']);
    HeroSlide::factory()->expired()->create(['title' => 'Aviso vencido']);

    $response = $this->get(route('public.home'));

    $response->assertOk();
    $response->assertSee('data-hero-slides-modal');
    $response->assertSee('Aviso vigente');
    $response->assertDontSee('Aviso vencido');
});

test('the home hero renders the entrance cascade, ken burns and parallax hooks', function () {
    $response = $this->get(route('public.home'));

    $response->assertOk();
    // Cascada de entrada
    $response->assertSee('hero-reveal');
    $response->assertSee('hero-reveal-scale');
    $response->assertSee('hero-reveal-right');
    $response->assertSee('hero-reveal-fade');
    // Ken Burns + rotación Alpine (sin parallax ni hovers con movimiento)
    $response->assertSee('hero-bg-layer');
    $response->assertDontSee('onHeroMouse');
    $response->assertDontSee('parallaxOn');
    $response->assertSee('initHero');
    // Performance: preload del primer fondo y prioridad del logo
    $response->assertSee('fondo-a.jpeg');
    $response->assertSee('fetchpriority="high"', false);
});
