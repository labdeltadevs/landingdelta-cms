<?php

use App\Models\SiteSetting;

afterEach(function () {
    SiteSetting::query()->where('key', 'about_milestones')->delete();
});

test('the about page renders when about_milestones is not set', function () {
    $response = $this->get(route('public.about'));

    $response->assertOk();
});

test('the about page renders when about_milestones is null', function () {
    SiteSetting::put('about_milestones', null);

    $response = $this->get(route('public.about'));

    $response->assertOk();
});

test('the about page renders when about_milestones is the string "null"', function () {
    SiteSetting::put('about_milestones', 'null');

    $response = $this->get(route('public.about'));

    $response->assertOk();
});

test('the about page renders when about_milestones contains invalid JSON', function () {
    SiteSetting::put('about_milestones', 'not-valid-json');

    $response = $this->get(route('public.about'));

    $response->assertOk();
});

test('the about page renders the timeline with a valid milestones array', function () {
    $milestones = [
        ['year' => 1987, 'title' => 'Fundación', 'desc' => 'Inicio de operaciones'],
        ['year' => 2000, 'title' => 'Expansión', 'desc' => 'Nueva planta'],
    ];

    SiteSetting::put('about_milestones', $milestones);

    $response = $this->get(route('public.about'));

    $response->assertOk();
    $response->assertSee('Fundación');
    $response->assertSee('1987');
});
