<?php

use App\Models\JobOpening;

function extractWorkJsonLd(string $html, ?string $type = null): ?array
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

test('el indice muestra las convocatorias publicadas', function () {
    JobOpening::factory()->create(['title' => 'Farmacéutico visible']);
    JobOpening::factory()->inactive()->create(['title' => 'Oculta inactiva']);
    JobOpening::factory()->expired()->create(['title' => 'Oculta vencida']);

    $response = $this->get(route('public.work-with-us'));

    $response->assertOk();
    $response->assertSee('Farmacéutico visible');
    $response->assertDontSee('Oculta inactiva');
    $response->assertDontSee('Oculta vencida');
});

test('el indice filtra por busqueda', function () {
    JobOpening::factory()->create(['title' => 'Contador Senior']);
    JobOpening::factory()->create(['title' => 'Chofer Reparto']);

    $response = $this->get(route('public.work-with-us', ['search' => 'Contador']));

    $response->assertOk();
    $response->assertSee('Contador Senior');
    $response->assertDontSee('Chofer Reparto');
});

test('el detalle muestra la convocatoria con jobposting valido', function () {
    $job = JobOpening::factory()->create([
        'title' => 'Químico Farmacéutico',
        'description' => 'Requisitos y beneficios del puesto.',
        'application_email' => 'rrhh@laboratoriosdelta.net',
    ]);

    $response = $this->get(route('public.work-with-us.show', $job));

    $response->assertOk();
    $response->assertSee('Químico Farmacéutico');
    $response->assertSee('mailto:rrhh@laboratoriosdelta.net', false);

    $jsonLd = extractWorkJsonLd($response->getContent(), 'JobPosting');

    expect($jsonLd)->not->toBeNull()
        ->and($jsonLd['title'] ?? null)->toBe('Químico Farmacéutico')
        ->and($jsonLd['hiringOrganization']['name'] ?? null)->toBe('Laboratorios Delta S.A.')
        ->and($jsonLd['datePosted'] ?? null)->not->toBeNull()
        ->and($jsonLd['validThrough'] ?? null)->not->toBeNull();
});

test('el detalle responde 404 para slug inexistente o no publicada', function () {
    $inactive = JobOpening::factory()->inactive()->create();
    $expired = JobOpening::factory()->expired()->create();

    $this->get('/trabaja-con-nosotros/slug-que-no-existe')->assertNotFound();
    $this->get(route('public.work-with-us.show', $inactive))->assertNotFound();
    $this->get(route('public.work-with-us.show', $expired))->assertNotFound();
});

test('el sitemap incluye las convocatorias publicadas', function () {
    $job = JobOpening::factory()->create();

    $response = $this->get('/sitemap.xml');

    $response->assertOk();
    $response->assertSee(route('public.work-with-us.show', $job), false);
});
