<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::factory()->create([
            'name' => 'Administrador',
            'email' => 'admin@laboratoriosdelta.net',
            'password' => Hash::make('password'),
            'must_change_password' => true,
        ]);
        $admin->assignRole('admin');

        $editor = User::factory()->create([
            'name' => 'Editor',
            'email' => 'editor@laboratoriosdelta.net',
            'password' => Hash::make('password'),
            'must_change_password' => true,
        ]);
        $editor->assignRole('editor');

        $visor = User::factory()->create([
            'name' => 'Visor',
            'email' => 'visor@laboratoriosdelta.net',
            'password' => Hash::make('password'),
            'must_change_password' => true,
        ]);
        $visor->assignRole('visor');
    }
}
