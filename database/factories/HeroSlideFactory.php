<?php

namespace Database\Factories;

use App\Models\HeroSlide;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<HeroSlide>
 */
class HeroSlideFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(4),
            'subtitle' => fake()->sentence(6),
            'image_path' => 'hero/slide-'.fake()->word().'.jpg',
            'cta_label' => 'Conoce más',
            'cta_url' => '/productos',
            'slideable_type' => null,
            'slideable_id' => null,
            'is_active' => true,
            'sort' => 0,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => ['is_active' => false]);
    }
}
