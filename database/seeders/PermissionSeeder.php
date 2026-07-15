<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'access admin',
            'manage users',
            'manage settings',
            'view content',
            'create content',
            'update content',
            'delete content',
            'manage hero',
            'manage brochures',
            'manage news',
        ];

        foreach ($permissions as $permission) {
            Permission::findOrCreate($permission, 'web');
        }

        $admin = Role::findOrCreate('admin', 'web');
        $admin->givePermissionTo(Permission::all());

        $editor = Role::findOrCreate('editor', 'web');
        $editor->givePermissionTo([
            'access admin',
            'view content',
            'create content',
            'update content',
            'delete content',
            'manage hero',
            'manage brochures',
            'manage news',
        ]);

        $visor = Role::findOrCreate('visor', 'web');
        $visor->givePermissionTo([
            'access admin',
            'view content',
        ]);
    }
}
