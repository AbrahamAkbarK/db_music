<?php

namespace Database\Factories;

use App\Models\Album;
use App\Models\Artist;
use App\Models\Composer;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Song>
 */
class SongFactory extends Factory
{
    protected $model = \App\Models\Song::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->sentence(4);

        return [
            'title' => $title,
            'slug' => Str::slug($title),
            'album_id' => Album::factory(),
            'track_number' => $this->faker->numberBetween(1, 15),
            'duration_seconds' => $this->faker->numberBetween(120, 360), // 2-6 minutes
            'genre' => $this->faker->randomElement(['Pop', 'Rock', 'Hip Hop', 'Electronic', 'R&B']),
            'lyrics' => $this->faker->paragraphs(8, true),
            'isrc_code' => $this->faker->unique()->regexify('[A-Z]{2}[A-Z0-9]{3}[0-9]{7}'),
            'price' => $this->faker->randomFloat(2, 0.99, 2.99),
            'status' => $this->faker->randomElement(['draft', 'recorded', 'mixed', 'mastered', 'released']),
            'composer' => $this->faker->name(),
            'lyricist' => $this->faker->name(),
            'arranger' => $this->faker->name(),
            'is_explicit' => $this->faker->boolean(20), // 20% chance of being explicit
            'royalty_contract'=> $this->faker->randomElement(['Flat','Royalty Based']),
            'label'=> $this->faker->randomElement(['PT Aquarius Musikindo']),
            'artist_id' => Artist::factory(),
            'composer_id'=> Composer::factory(),
        ];
    }
}
