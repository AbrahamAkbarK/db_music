<?php

namespace Database\Factories;

use App\Models\Link;
use App\Models\Song;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Link>
 */
class LinkFactory extends Factory
{
    protected $model = Link::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'song_id' => Song::factory(),
            'spotify_url' => 'https://open.spotify.com/song/' . $this->faker->uuid(),
            'apple_music_url' => 'https://music.apple.com/song/' . $this->faker->uuid(),
            'youtube_url' => 'https://www.youtube.com/channel/' . $this->faker->uuid(),
            'instagram_url' => 'https://www.instagram.com/' . $this->faker->uuid(),
            'tiktok_url' => 'https://www.tiktok.com/' . $this->faker->uuid(),
            'langit_musik_url' => 'https://play.langitmusik.co.id/' . $this->faker->uuid(),
            'link_fire_url' => 'https://www.linkfire.com/' . $this->faker->uuid(),
            'trebel_url' => 'https://home.trebel.io/' . $this->faker->uuid(),
            'youtube_musik_url' => 'https://music.youtube.com/' . $this->faker->uuid(),
        ];
    }
}
