<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Album>
 */
class AlbumFactory extends Factory
{
    protected $model = \App\Models\Album::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(3);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'description' => $this->faker->paragraphs(2, true),
            'genre' => $this->faker->randomElement(['Pop', 'Rock', 'Hip Hop', 'Electronic', 'R&B']),
            'subgenre' => $this->faker->randomElement(['Indie', 'Alternative', 'Mainstream', 'Underground']),
            'release_date' => $this->faker->dateTimeBetween('-2 years', '+1 year'),
            'type' => $this->faker->randomElement(['album', 'ep', 'single', 'compilation']),
            'upc_code' => $this->faker->unique()->numerify('############'),
            'price' => $this->faker->randomFloat(2, 9.99, 29.99),
            'status' => $this->faker->randomElement(['draft', 'scheduled', 'released', 'archived']),
            'producer' => $this->faker->name(),
            'record_label' => $this->faker->company(),
            'recording_studio' => $this->faker->company() . ' Studios',
            'recording_year' => $this->faker->year(),
        ];
    }
}
