<?php

namespace Database\Factories;

use App\Models\News;
use App\Models\NewsImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<NewsImage>
 */
class NewsImageFactory extends Factory
{
    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'news_id' => News::factory(),
            'path' => 'news/'.fake()->word().'.jpg',
            'sort' => 0,
        ];
    }
}
