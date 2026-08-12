<?php

use App\Livewire\Admin\Hero\HeroSlideForm;
use App\Livewire\Admin\Hero\HeroSlideIndex;
use App\Models\HeroSlide;
use App\Models\User;
use Carbon\Carbon;
use Database\Seeders\PermissionSeeder;
use Livewire\Livewire;

test('crea un slide guardando el rango de vigencia', function () {
    $this->seed(PermissionSeeder::class);
    $this->actingAs(User::factory()->withRole('admin')->create());

    Livewire::test(HeroSlideForm::class)
        ->set('title', 'Promo de agosto')
        ->set('valid_from', '2026-08-01')
        ->set('valid_until', '2026-08-31')
        ->call('save')
        ->assertRedirect(route('admin.hero.index'));

    $slide = HeroSlide::where('title', 'Promo de agosto')->firstOrFail();

    expect($slide->valid_from->toDateString())->toBe('2026-08-01')
        ->and($slide->valid_until->toDateString())->toBe('2026-08-31');
});

test('carga las fechas de vigencia al editar un slide', function () {
    $this->seed(PermissionSeeder::class);
    $this->actingAs(User::factory()->withRole('admin')->create());

    $slide = HeroSlide::factory()->create([
        'title' => 'Aviso vigente',
        'valid_from' => '2026-08-01',
        'valid_until' => '2026-08-31',
    ]);

    Livewire::test(HeroSlideForm::class, ['heroSlide' => $slide])
        ->assertSet('valid_from', '2026-08-01')
        ->assertSet('valid_until', '2026-08-31');
});

test('no permite que la fecha fin sea anterior a la fecha inicio', function () {
    $this->seed(PermissionSeeder::class);
    $this->actingAs(User::factory()->withRole('admin')->create());

    Livewire::test(HeroSlideForm::class)
        ->set('title', 'Aviso inválido')
        ->set('valid_from', '2026-08-31')
        ->set('valid_until', '2026-08-01')
        ->call('save')
        ->assertHasErrors('valid_until');

    expect(HeroSlide::count())->toBe(0);
});

test('guardar sin fechas limpia la vigencia del slide', function () {
    $this->seed(PermissionSeeder::class);
    $this->actingAs(User::factory()->withRole('admin')->create());

    $slide = HeroSlide::factory()->current()->create(['title' => 'Con fechas']);

    Livewire::test(HeroSlideForm::class, ['heroSlide' => $slide])
        ->set('valid_from', '')
        ->set('valid_until', '')
        ->call('save')
        ->assertRedirect(route('admin.hero.index'));

    $slide->refresh();

    expect($slide->valid_from)->toBeNull()
        ->and($slide->valid_until)->toBeNull();
});

test('el scope valid filtra por el rango de fechas', function () {
    HeroSlide::factory()->current()->create(['title' => 'Vigente']);
    HeroSlide::factory()->expired()->create(['title' => 'Vencido']);
    HeroSlide::factory()->upcoming()->create(['title' => 'Futuro']);
    HeroSlide::factory()->create(['title' => 'Sin fechas']);

    expect(HeroSlide::query()->valid()->orderBy('id')->pluck('title')->all())
        ->toBe(['Vigente', 'Sin fechas']);
});

test('isValid respeta la fecha consultada', function () {
    $slide = HeroSlide::factory()->create([
        'valid_from' => '2026-08-10',
        'valid_until' => '2026-08-20',
    ]);

    expect($slide->isValid(Carbon::parse('2026-08-15')))->toBeTrue()
        ->and($slide->isValid(Carbon::parse('2026-08-10')))->toBeTrue()
        ->and($slide->isValid(Carbon::parse('2026-08-09')))->toBeFalse()
        ->and($slide->isValid(Carbon::parse('2026-08-21')))->toBeFalse();
});

test('el indice muestra el rango y el estado de vigencia de cada slide', function () {
    $this->seed(PermissionSeeder::class);
    $this->actingAs(User::factory()->withRole('admin')->create());

    $from = now()->subDay();
    $until = now()->addDay();

    HeroSlide::factory()->create([
        'title' => 'Vigente hoy',
        'valid_from' => $from->toDateString(),
        'valid_until' => $until->toDateString(),
    ]);
    HeroSlide::factory()->expired()->create(['title' => 'Ya vencido']);

    Livewire::test(HeroSlideIndex::class)
        ->assertSee('Vigente hoy')
        ->assertSee($from->format('d/m/Y').' → '.$until->format('d/m/Y'))
        ->assertSee('Ya vencido')
        ->assertSee('Vigente')
        ->assertSee('Fuera de vigencia');
});
