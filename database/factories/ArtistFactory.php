<?php

namespace Database\Factories;

use App\Models\Artist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Artist>
 */
class ArtistFactory extends Factory
{
    protected $model = Artist::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'stage_name' => $this->faker->userName(),
            'biography' => $this->faker->paragraphs(3, true),
            'genre' => $this->faker->randomElement(['Pop', 'Rock', 'Hip Hop', 'Electronic', 'R&B']),
            'country' => $this->faker->country(),
            'birth_date' => $this->faker->dateTimeBetween('-50 years', '-18 years'),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'website' => $this->faker->url(),
            'category' => $this->faker->randomElement(['solo', 'Group Band']),
            'manager' => $this->faker->name(),
            'spotify_url' => 'https://open.spotify.com/artist/' . $this->faker->uuid(),
            'apple_music_url' => 'https://music.apple.com/artist/' . $this->faker->uuid(),
            'youtube_url' => 'https://www.youtube.com/channel/' . $this->faker->uuid(),
            'instagram_url' => 'https://www.instagram.com/' . $this->faker->userName(),
            'facebook_url' => 'https://www.facebook.com/' . $this->faker->userName(),
            'twitter_url' => 'https://www.twitter.com/' . $this->faker->userName(),
            'status' => $this->faker->randomElement(['active', 'inactive', 'on_hold']),
            'contract_start_date' => $this->faker->dateTimeBetween('-10 years', '-5 years'),
            'contract_end_date' => $this->faker->dateTimeBetween('-3 years', '+1 years'),
        ];
    }
}
