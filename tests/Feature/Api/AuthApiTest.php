<?php

use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function (): void {
    $this->seed(PermissionSeeder::class);
});

test('login issues a permanent bearer token for valid credentials', function (): void {
    $user = User::factory()->create([
        'email' => 'admin@laboratoriosdelta.net',
        'password' => Hash::make('secret-password'),
    ]);
    $user->assignRole('admin');

    $response = $this->postJson(route('api.login'), [
        'email' => 'admin@laboratoriosdelta.net',
        'password' => 'secret-password',
        'device_name' => 'integration-tests',
    ]);

    $response->assertSuccessful()
        ->assertJsonStructure([
            'token',
            'token_name',
            'token_type',
            'user' => ['id', 'name', 'email', 'roles', 'permissions', 'must_change_password'],
        ])
        ->assertJson([
            'token_type' => 'Bearer',
            'token_name' => 'integration-tests',
        ]);

    expect($response->json('user.permissions'))->toBeArray();

    expect($response->json('token'))->toBeString()->not->toBeEmpty();

    $this->assertDatabaseHas('personal_access_tokens', [
        'tokenable_id' => $user->id,
        'name' => 'integration-tests',
        'expires_at' => null,
    ]);
});

test('login rejects invalid credentials', function (): void {
    User::factory()->create([
        'email' => 'admin@laboratoriosdelta.net',
        'password' => Hash::make('secret-password'),
    ]);

    $this->postJson(route('api.login'), [
        'email' => 'admin@laboratoriosdelta.net',
        'password' => 'wrong-password',
    ])->assertStatus(422)
        ->assertJsonValidationErrors('email');
});

test('login requires email and password', function (): void {
    $this->postJson(route('api.login'), [])
        ->assertStatus(422)
        ->assertJsonValidationErrors(['email', 'password']);
});

test('authenticated user can fetch their own data using bearer token', function (): void {
    $user = User::factory()->create([
        'email' => 'editor@laboratoriosdelta.net',
    ]);
    $user->assignRole('editor');

    Sanctum::actingAs($user, ['*']);

    $this->getJson(route('api.user'))
        ->assertSuccessful()
        ->assertJsonPath('user.email', 'editor@laboratoriosdelta.net')
        ->assertJsonPath('user.roles.0', 'editor')
        ->assertJsonStructure([
            'user' => ['id', 'name', 'email', 'roles', 'permissions', 'must_change_password', 'tokens'],
        ]);

    expect($this->json('user.permissions'))->toBeArray();
});

test('unauthenticated user cannot access protected api routes', function (): void {
    $this->getJson(route('api.user'))
        ->assertStatus(401);
});

test('admin and editor can generate a new bearer token via api', function (): void {
    $admin = User::factory()->create(['email' => 'admin@laboratoriosdelta.net']);
    $admin->assignRole('admin');

    Sanctum::actingAs($admin, ['*']);

    $this->postJson(route('api.token.generate'), [
        'device_name' => 'external-system',
    ])
        ->assertSuccessful()
        ->assertJson([
            'token_type' => 'Bearer',
            'token_name' => 'external-system',
        ])
        ->assertJsonStructure([
            'token',
            'token_name',
            'token_type',
            'user' => ['id', 'name', 'email', 'roles', 'permissions'],
        ]);

    expect($this->json('user.permissions'))->toBeArray();

    $this->assertDatabaseHas('personal_access_tokens', [
        'tokenable_id' => $admin->id,
        'name' => 'external-system',
        'expires_at' => null,
    ]);
});

test('visor role cannot generate a new api token', function (): void {
    $visor = User::factory()->create(['email' => 'visor@laboratoriosdelta.net']);
    $visor->assignRole('visor');

    Sanctum::actingAs($visor, ['*']);

    $this->postJson(route('api.token.generate'), [
        'device_name' => 'should-be-denied',
    ])->assertStatus(403);
});

test('revoke endpoint deletes the current token', function (): void {
    $user = User::factory()->create(['email' => 'admin@laboratoriosdelta.net']);
    $user->assignRole('admin');

    Sanctum::actingAs($user, ['*']);

    $this->postJson(route('api.token.revoke'))
        ->assertSuccessful()
        ->assertJson([
            'message' => __('Token revocado correctamente.'),
        ]);

    expect($user->fresh()->tokens()->count())->toBe(0);
});

test('logout deletes current token and rejects subsequent calls', function (): void {
    $user = User::factory()->create(['email' => 'editor@laboratoriosdelta.net']);
    $user->assignRole('editor');

    Sanctum::actingAs($user, ['*']);

    $this->postJson(route('api.logout'))
        ->assertSuccessful();

    expect($user->fresh()->tokens()->count())->toBe(0);
});

test('issued token grants access to protected api routes', function (): void {
    $user = User::factory()->create(['email' => 'admin@laboratoriosdelta.net']);
    $user->assignRole('admin');

    $loginResponse = $this->postJson(route('api.login'), [
        'email' => 'admin@laboratoriosdelta.net',
        'password' => 'password',
    ]);

    $token = $loginResponse->json('token');

    $this->withHeaders([
        'Authorization' => 'Bearer '.$token,
        'Accept' => 'application/json',
    ])->getJson(route('api.user'))
        ->assertSuccessful()
        ->assertJsonPath('user.email', 'admin@laboratoriosdelta.net')
        ->assertJsonStructure([
            'user' => ['id', 'name', 'email', 'roles', 'permissions', 'must_change_password', 'tokens'],
        ]);

    expect($this->json('user.permissions'))->toBeArray();
});
