<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = strtoupper(fake()->unique()->words(2, true));

        return [
            'name' => $name,
            'slug' => Str::slug($name).'-'.Str::lower(Str::random(4)),
            'internal_code' => 'PROD-'.fake()->unique()->numberBetween(1000, 9999),
            'active_ingredient' => fake()->word(),
            'description' => fake()->paragraph(),
            'approx_price' => fake()->randomFloat(2, 5, 200),
            'brand_id' => null,
            'category_id' => null,
            'main_image_path' => null,
            'is_active' => true,
            'is_featured' => false,
            'sort' => 0,
        ];
    }

    public function featured(): static
    {
        return $this->state(fn (): array => ['is_featured' => true]);
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => ['is_active' => false]);
    }
}
