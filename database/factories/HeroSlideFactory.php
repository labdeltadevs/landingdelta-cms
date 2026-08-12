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
            'valid_from' => null,
            'valid_until' => null,
            'sort' => 0,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => ['is_active' => false]);
    }

    /** Vigente hoy (rango que incluye la fecha actual). */
    public function current(): static
    {
        return $this->state(fn (): array => [
            'valid_from' => now()->subDay()->toDateString(),
            'valid_until' => now()->addDay()->toDateString(),
        ]);
    }

    /** Vencido (la fecha fin ya paso). */
    public function expired(): static
    {
        return $this->state(fn (): array => [
            'valid_until' => now()->subDay()->toDateString(),
        ]);
    }

    /** Aun no vigente (la fecha inicio es futura). */
    public function upcoming(): static
    {
        return $this->state(fn (): array => [
            'valid_from' => now()->addDay()->toDateString(),
        ]);
    }
}
