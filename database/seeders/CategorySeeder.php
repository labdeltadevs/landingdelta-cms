<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Comprimidos, Cápsulas, Sobres, Tabletas', 'sort' => 0],
            ['name' => 'Suspensiones, Jarabes y Gotas', 'sort' => 1],
            ['name' => 'Cremas, Pomadas, Ungüentos', 'sort' => 2],
            ['name' => 'Inyectables', 'sort' => 3],
            ['name' => 'Soluciones y Colirios', 'sort' => 4],
        ];

        foreach ($categories as $cat) {
            Category::query()->create([
                'name' => $cat['name'],
                'slug' => Str::slug($cat['name']),
                'sort' => $cat['sort'],
                'is_active' => true,
            ]);
        }
    }
}
