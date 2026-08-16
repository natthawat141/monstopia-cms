<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Article>
 */
class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        $title = fake()->unique()->sentence(5);
        return [
            'category_id' => Category::factory(),
            'title' => $title,
            'slug' => str()->slug($title) . '-' . fake()->unique()->numberBetween(100, 999),
            'summary' => fake()->sentence(20),
            'content' => fake()->paragraphs(4, true),
            'cover_image' => null,
            'status' => fake()->randomElement(['draft', 'published', 'archived']),
            'published_at' => fake()->optional()->dateTimeBetween('-1 year', 'now'),
        ];
    }
}
