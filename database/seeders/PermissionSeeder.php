<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'view.products', 'edit.products',
            'view.brands', 'edit.brands',
            'view.categories', 'edit.categories',
            'view.branches', 'edit.branches',
            'view.hero', 'edit.hero',
            'view.brochures', 'edit.brochures',
            'view.news', 'edit.news',
            'view.job-openings', 'edit.job-openings',
            'view.users', 'edit.users',
            'view.settings', 'edit.settings',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $admin = Role::findOrCreate('admin', 'web');
        $admin->givePermissionTo(Permission::all());

        $editor = Role::findOrCreate('editor', 'web');
        $editor->givePermissionTo([
            'view.products', 'edit.products',
            'view.brands', 'edit.brands',
            'view.categories', 'edit.categories',
            'view.branches', 'edit.branches',
            'view.hero', 'edit.hero',
            'view.brochures', 'edit.brochures',
            'view.news', 'edit.news',
            'view.job-openings', 'edit.job-openings',
        ]);

        $visor = Role::findOrCreate('visor', 'web');
        $visor->givePermissionTo([
            'view.products',
            'view.brands',
            'view.categories',
            'view.branches',
            'view.hero',
            'view.brochures',
            'view.news',
            'view.job-openings',
        ]);
    }
}
