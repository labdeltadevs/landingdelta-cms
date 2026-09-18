<?php

namespace Database\Factories;

use App\Models\JobOpening;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<JobOpening>
 */
class JobOpeningFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->jobTitle();

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.Str::lower(Str::random(4)),
            'description' => fake()->paragraphs(3, true),
            'valid_from' => now()->subDays(fake()->numberBetween(0, 15))->format('Y-m-d'),
            'valid_until' => now()->addDays(fake()->numberBetween(15, 60))->format('Y-m-d'),
            'image_path' => null,
            'application_email' => 'rrhh@laboratoriosdelta.net',
            'is_active' => true,
            'sort' => 0,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => ['is_active' => false]);
    }

    public function expired(): static
    {
        return $this->state(fn (): array => [
            'valid_until' => now()->subDay()->format('Y-m-d'),
        ]);
    }
}
