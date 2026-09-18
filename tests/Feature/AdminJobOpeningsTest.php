<?php

use App\Livewire\Admin\JobOpenings\JobOpeningForm;
use App\Livewire\Admin\JobOpenings\JobOpeningIndex;
use App\Models\JobOpening;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Livewire\Livewire;

test('el slug se genera automaticamente al escribir el titulo', function () {
    $this->seed(PermissionSeeder::class);
    $this->actingAs(User::factory()->withRole('admin')->create());

    Livewire::test(JobOpeningForm::class)
        ->set('title', 'Farmacéutico Turno Noche')
        ->assertSet('slug', fn ($slug) => filled($slug) && str_starts_with($slug, 'farmaceutico-turno-noche'));
});

test('guardar persiste el slug y editarlo conserva el existente', function () {
    $this->seed(PermissionSeeder::class);
    $this->actingAs(User::factory()->withRole('admin')->create());

    Livewire::test(JobOpeningForm::class)
        ->set('title', 'Contador Junior')
        ->set('description', 'Descripción del puesto.')
        ->set('valid_from', now()->format('Y-m-d'))
        ->set('valid_until', now()->addMonth()->format('Y-m-d'))
        ->call('save')
        ->assertRedirect(route('admin.job-openings.index'));

    $job = JobOpening::where('title', 'Contador Junior')->firstOrFail();

    expect($job->slug)->not->toBeNull();

    $originalSlug = $job->slug;

    Livewire::test(JobOpeningForm::class, ['jobOpening' => $job])
        ->assertSet('slug', $originalSlug)
        ->set('title', 'Contador Senior')
        ->call('save')
        ->assertRedirect(route('admin.job-openings.index'));

    expect($job->refresh()->slug)->toBe($originalSlug);
});

test('genera el QR con la URL publica de la convocatoria', function () {
    $this->seed(PermissionSeeder::class);
    $this->actingAs(User::factory()->withRole('admin')->create());

    $job = JobOpening::factory()->create();

    Livewire::test(JobOpeningIndex::class)
        ->call('showQr', $job->id)
        ->assertSet('qrUrl', route('public.work-with-us.show', $job))
        ->assertSet('qrName', $job->title)
        ->assertSet('barcodeValue', $job->slug)
        ->assertSee('<svg', false);
});
