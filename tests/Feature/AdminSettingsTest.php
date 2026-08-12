<?php

use App\Models\User;
use Database\Seeders\PermissionSeeder;

test('admins can visit the site configuration panel', function () {
    $this->seed(PermissionSeeder::class);
    $user = User::factory()->withRole('admin')->create();
    $this->actingAs($user);

    $response = $this->get(route('admin.settings.index'));

    $response->assertOk();
    $response->assertSee('Configuración del sitio');
    $response->assertSee('Información general');
    $response->assertSee('Página Nosotros');
    $response->assertSee('Página Trabajá con Nosotros');
    $response->assertSee('Redes Sociales');
    $response->assertSee('Título de la página');
});

test('users without the settings permission cannot access the panel', function () {
    $this->seed(PermissionSeeder::class);
    $user = User::factory()->withRole('visor')->create();
    $this->actingAs($user);

    $response = $this->get(route('admin.settings.index'));

    $response->assertForbidden();
});
