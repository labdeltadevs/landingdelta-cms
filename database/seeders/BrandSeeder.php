<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Delta Care', 'description' => 'Línea de productos de cuidado personal y salud familiar.'],
            ['name' => 'Delta Pharma', 'description' => 'Especialidades farmacéuticas de alta calidad.'],
        ];

        foreach ($brands as $brand) {
            Brand::query()->create([
                'name' => $brand['name'],
                'slug' => Str::slug($brand['name']),
                'description' => $brand['description'],
                'is_active' => true,
                'sort' => 0,
            ]);
        }
    }
}
