<?php

namespace Database\Factories;

use App\Models\Branch;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Branch>
 */
class BranchFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => 'Sucursal '.fake()->city(),
            'city' => fake()->randomElement(['La Paz', 'Santa Cruz', 'Cochabamba', 'Chuquisaca']),
            'address' => fake()->streetAddress(),
            'phone' => '2 '.fake()->numerify('### ####'),
            'phone_2' => fake()->numerify('7#######'),
            'email' => fake()->companyEmail(),
            'lat' => fake()->latitude(-20, -15),
            'lng' => fake()->longitude(-69, -57),
            'is_active' => true,
            'sort' => 0,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => ['is_active' => false]);
    }
}
