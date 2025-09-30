<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Genre;

class GenreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $genres = [
            ['name' => 'Pop', 'slug' => 'pop', 'description' => 'Popular music genre'],
            ['name' => 'Rock', 'slug' => 'rock', 'description' => 'Rock music genre'],
            ['name' => 'Hip Hop', 'slug' => 'hip-hop', 'description' => 'Hip hop and rap music'],
            ['name' => 'Electronic', 'slug' => 'electronic', 'description' => 'Electronic music'],
            ['name' => 'R&B', 'slug' => 'rnb', 'description' => 'Rhythm and Blues'],
            ['name' => 'Country', 'slug' => 'country', 'description' => 'Country music'],
            ['name' => 'Jazz', 'slug' => 'jazz', 'description' => 'Jazz music'],
            ['name' => 'Classical', 'slug' => 'classical', 'description' => 'Classical music'],
            ['name' => 'Reggae', 'slug' => 'reggae', 'description' => 'Reggae music'],
            ['name' => 'Alternative', 'slug' => 'alternative', 'description' => 'Alternative music'],
        ];

        foreach ($genres as $genre) {
            Genre::create($genre);
        }
    }
}
