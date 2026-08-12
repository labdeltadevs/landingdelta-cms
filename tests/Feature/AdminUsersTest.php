<?php

use App\Livewire\Admin\Users\UserForm;
use App\Models\User;
use Database\Seeders\PermissionSeeder;
use Livewire\Livewire;

test('the edit form shows the permissions granted by the role as checked', function () {
    $this->seed(PermissionSeeder::class);
    $admin = User::factory()->withRole('admin')->create();
    $this->actingAs($admin);

    $editor = User::factory()->withRole('editor')->create();

    $permissions = Livewire::test(UserForm::class, ['user' => $editor])
        ->get('permissions');

    expect($permissions)
        ->toHaveCount(9)
        ->toContain('view.products')
        ->toContain('edit.products')
        ->toContain('view.brands')
        ->toContain('view.categories')
        ->toContain('view.branches')
        ->toContain('view.hero')
        ->toContain('view.brochures')
        ->toContain('view.news')
        ->toContain('view.job-openings')
        ->not->toContain('view.users')
        ->not->toContain('view.settings');
});

test('saving an editor without changes does not duplicate role permissions as direct permissions', function () {
    $this->seed(PermissionSeeder::class);
    $admin = User::factory()->withRole('admin')->create();
    $this->actingAs($admin);

    $editor = User::factory()->withRole('editor')->create();

    Livewire::test(UserForm::class, ['user' => $editor])
        ->call('save');

    $editor->refresh();

    expect($editor->getDirectPermissions()->pluck('name')->toArray())->toBe([]);
    expect($editor->getAllPermissions()->pluck('name'))->toHaveCount(9);
});

test('permissions checked beyond the role are saved as direct permissions', function () {
    $this->seed(PermissionSeeder::class);
    $admin = User::factory()->withRole('admin')->create();
    $this->actingAs($admin);

    $editor = User::factory()->withRole('editor')->create();

    $rolePermissions = [
        'view.products', 'edit.products',
        'view.brands',
        'view.categories',
        'view.branches',
        'view.hero',
        'view.brochures',
        'view.news',
        'view.job-openings',
    ];

    Livewire::test(UserForm::class, ['user' => $editor])
        ->set('permissions', [...$rolePermissions, 'edit.news'])
        ->call('save');

    $editor->refresh();

    expect($editor->getDirectPermissions()->pluck('name')->toArray())->toBe(['edit.news']);
});
