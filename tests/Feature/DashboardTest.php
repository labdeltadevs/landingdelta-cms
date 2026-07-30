<?php

use App\Models\User;
use Database\Seeders\PermissionSeeder;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('admin.dashboard'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the dashboard', function () {
    $this->seed(PermissionSeeder::class);
    $user = User::factory()->withRole('visor')->create();
    $this->actingAs($user);

    $response = $this->get(route('admin.dashboard'));
    $response->assertOk();
});
