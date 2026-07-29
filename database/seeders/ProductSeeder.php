<?php

namespace Database\Seeders;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $category = Category::query()->first();
        $brand = Brand::query()->first();

        $products = [
            [
                'name' => 'ACETAZOLAMIDA 250 mg',
                'internal_code' => 'ACZ-250',
                'active_ingredient' => 'Acetazolamida',
                'category_index' => 0,
            ],
            [
                'name' => 'ACETILCISTEÍNA 600 mg',
                'internal_code' => 'ACT-600',
                'active_ingredient' => 'Acetilcisteína',
                'category_index' => 0,
            ],
            [
                'name' => 'ACICLOVIR 400 mg',
                'internal_code' => 'ACV-400',
                'active_ingredient' => 'Aciclovir',
                'category_index' => 0,
            ],
            [
                'name' => 'AMBROXOL JARABE 15mg/5ml',
                'internal_code' => 'AMB-15',
                'active_ingredient' => 'Ambroxol',
                'category_index' => 1,
            ],
            [
                'name' => 'AMOXICILINA 500 mg',
                'internal_code' => 'AMX-500',
                'active_ingredient' => 'Amoxicilina',
                'category_index' => 0,
            ],
            [
                'name' => 'ACICLOVIR CREMA 5%',
                'internal_code' => 'ACV-5',
                'active_ingredient' => 'Aciclovir',
                'category_index' => 2,
            ],
        ];

        $categories = Category::query()->pluck('id');

        foreach ($products as $i => $product) {
            Product::query()->create([
                'name' => $product['name'],
                'internal_code' => $product['internal_code'],
                'slug' => Str::slug($product['name']),
                'active_ingredient' => $product['active_ingredient'],
                'description' => fake()->paragraph(),
                'approx_price' => fake()->randomFloat(2, 15, 120),
                'brand_id' => $brand?->id,
                'category_id' => $category ? ($categories[$product['category_index']] ?? $category->id) : null,
                'is_active' => true,
                'is_featured' => $i < 3,
                'sort' => $i,
            ]);
        }
    }
}
