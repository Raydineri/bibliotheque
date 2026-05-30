<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Author;
use App\Models\Category;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $total = $this->faker->numberBetween(1, 5);
        return [
            'title'            => $this->faker->sentence(3),
            'isbn'             => $this->faker->unique()->isbn13(),
            'description'      => $this->faker->paragraph(),
            'total_copies'     => $total,
            'available_copies' => $this->faker->numberBetween(0, $total),
            'published_year'   => $this->faker->numberBetween(1990, 2024),
            'author_id'        => Author::factory(),
            'category_id'      => Category::factory(),
        ];
    }
}
