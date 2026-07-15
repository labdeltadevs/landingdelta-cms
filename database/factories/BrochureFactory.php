<?php

namespace Database\Factories;

use App\Models\Brochure;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Brochure>
 */
class BrochureFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => 'Línea '.fake()->word(),
            'description' => fake()->sentence(8),
            'file_path' => 'brochures/'.fake()->word().'.pdf',
            'line' => fake()->randomElement(['Gástricos', 'Otológicos', 'Antiinflamatorios', 'Antihistamínicos']),
            'is_active' => true,
            'sort' => 0,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => ['is_active' => false]);
    }
}
