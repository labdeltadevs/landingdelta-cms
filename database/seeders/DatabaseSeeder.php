<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        $this->call([
            PermissionSeeder::class,
            UserSeeder::class,
            CategorySeeder::class,
            BranchSeeder::class,
            SiteSettingSeeder::class,
            BrandSeeder::class,
            ProductSeeder::class,
            BrochureSeeder::class,
            NewsSeeder::class,
        ]);
    }
}
