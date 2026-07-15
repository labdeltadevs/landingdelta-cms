<?php

namespace Database\Factories;

use App\Models\News;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<News>
 */
class NewsFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->sentence(5);

        return [
            'title' => $title,
            'slug' => Str::slug($title).'-'.Str::lower(Str::random(4)),
            'excerpt' => fake()->paragraph(),
            'body' => fake()->paragraphs(3, true),
            'cover_image_path' => null,
            'published_at' => now()->subDays(fake()->numberBetween(0, 30)),
            'is_active' => true,
            'sort' => 0,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (): array => ['is_active' => false]);
    }

    public function draft(): static
    {
        return $this->state(fn (): array => ['published_at' => null]);
    }
}
