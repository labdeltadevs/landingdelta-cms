<?php

namespace Database\Seeders;

use App\Models\Brochure;
use Illuminate\Database\Seeder;

class BrochureSeeder extends Seeder
{
    public function run(): void
    {
        Brochure::query()->create([
            'title' => 'Línea Gástricos',
            'description' => 'Rotafolio de la línea de productos gástricos.',
            'file_path' => 'brochures/placeholder.pdf',
            'line' => 'Gástricos',
            'is_active' => true,
            'sort' => 0,
        ]);
    }
}
