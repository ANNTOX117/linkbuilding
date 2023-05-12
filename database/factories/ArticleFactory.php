<?php

namespace Database\Factories;

use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ArticleFactory extends Factory
{
    protected $model = Article::class;
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $title = $this->faker->sentence();
        return [
            "url" => Str::slug($title),
            "title" => $title,
            "description" => $this->faker->paragraph(),
            "image" => $this->faker->imageUrl(640, 480, 'animals', true),
            "category" => 3
        ];
    }
}
