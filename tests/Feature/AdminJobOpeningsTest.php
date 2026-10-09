<?php

use App\Livewire\Admin\JobOpenings\JobOpeningForm;
use App\Livewire\Admin\JobOpenings\JobOpeningIndex;
use App\Models\JobOpening;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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

test('la imagen subida se guarda y se muestra en la convocatoria', function () {
    Storage::fake('public');
    Storage::fake('local');

    $this->seed(PermissionSeeder::class);
    $this->actingAs(User::factory()->withRole('admin')->create());

    // valid_from en un día pasado: en SQLite el scope published() compara texto
    // ('Y-m-d H:i:s' <= 'Y-m-d' da false el día exacto), en producción (PostgreSQL,
    // columna date) no ocurre. El test verifica el pipeline de imagen, no ese caso borde.
    $from = now()->subDay()->format('Y-m-d');
    $until = now()->addWeek()->format('Y-m-d');

    Livewire::test(JobOpeningForm::class)
        ->set('title', 'Convocatoria con imagen')
        ->set('description', 'Descripción del puesto.')
        ->set('valid_from', $from)
        ->set('valid_until', $until)
        ->set('image', UploadedFile::fake()->image('convocatoria.jpg'))
        ->call('save')
        ->assertHasNoErrors()
        ->assertRedirect(route('admin.job-openings.index'));

    $job = JobOpening::where('title', 'Convocatoria con imagen')->firstOrFail();

    expect($job->image_path)->not->toBeNull()
        ->and($job->image_path)->toStartWith('job-openings/')
        ->and(Storage::disk('public')->exists($job->image_path))->toBeTrue()
        ->and(JobOpening::published()->get()->modelKeys())->toContain($job->getKey());

    $this->get(route('public.work-with-us'))
        ->assertOk()
        ->assertSee('/storage/'.$job->image_path, false);
});

test('editar reemplaza la imagen y elimina la anterior', function () {
    Storage::fake('public');
    Storage::fake('local');

    $this->seed(PermissionSeeder::class);
    $this->actingAs(User::factory()->withRole('admin')->create());

    $job = JobOpening::factory()->create(['image_path' => null]);

    $component = Livewire::test(JobOpeningForm::class, ['jobOpening' => $job])
        ->set('image', UploadedFile::fake()->image('primera.jpg'))
        ->call('save')
        ->assertHasNoErrors();

    $job->refresh();
    $primeraImagen = $job->image_path;

    expect($primeraImagen)->not->toBeNull()
        ->and(Storage::disk('public')->exists($primeraImagen))->toBeTrue();

    $component->set('image', UploadedFile::fake()->image('segunda.jpg'))
        ->call('save')
        ->assertHasNoErrors();

    $job->refresh();

    expect($job->image_path)->not->toBe($primeraImagen)
        ->and(Storage::disk('public')->exists($job->image_path))->toBeTrue()
        ->and(Storage::disk('public')->exists($primeraImagen))->toBeFalse();
});

test('el indice indica con un icono si la oferta tiene imagen asignada', function () {
    $this->seed(PermissionSeeder::class);
    $this->actingAs(User::factory()->withRole('admin')->create());

    JobOpening::factory()->create(['title' => 'Con Foto', 'image_path' => 'job-openings/foto.webp']);
    JobOpening::factory()->create(['title' => 'Sin Foto', 'image_path' => null]);

    Livewire::test(JobOpeningIndex::class)
        ->assertSee('Con Foto', false)
        ->assertSee('Sin Foto', false)
        ->assertSee('Con imagen:', false)
        ->assertSee('Sin imagen:', false)
        ->assertSee('text-emerald-500', false)
        ->assertSee('text-zinc-300', false);
});

test('genera el QR con la URL publica de la convocatoria', function () {
    $this->seed(PermissionSeeder::class);
    $this->actingAs(User::factory()->withRole('admin')->create());

    $job = JobOpening::factory()->create();

    Livewire::test(JobOpeningIndex::class)
        ->call('showQr', $job->id)
        ->assertSet('qrUrl', route('public.work-with-us.show', $job))
        ->assertSet('qrName', $job->title)
        ->assertSet('codeFilename', 'codigo-convocatoria-'.$job->slug)
        ->assertSet('barcodeSvg', null)
        ->assertSet('barcodeValue', null)
        ->assertSee('<svg', false);
});

test('genera el codigo de barras de la convocatoria sin QR', function () {
    $this->seed(PermissionSeeder::class);
    $this->actingAs(User::factory()->withRole('admin')->create());

    $job = JobOpening::factory()->create();

    Livewire::test(JobOpeningIndex::class)
        ->call('showBarcode', $job->id)
        ->assertSet('barcodeValue', $job->slug)
        ->assertSet('codeFilename', 'codigo-convocatoria-'.$job->slug)
        ->assertSet('qrSvg', null)
        ->assertSet('qrUrl', null)
        ->assertSee('<svg', false);
});
