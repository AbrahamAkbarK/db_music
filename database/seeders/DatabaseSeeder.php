<?php

namespace Database\Seeders;

use App\Models\Song;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use App\Models\Album;
use App\Models\Genre;
use App\Models\Artist;
use App\Models\Composer;
use App\Models\Contract;
use App\Models\Link;
use App\Models\Member;
use Illuminate\Database\Seeder;
use Database\Seeders\SongSeeder;
use Database\Seeders\AlbumSeeder;
use Database\Seeders\GenreSeeder;
use Database\Seeders\ArtistSeeder;
use Database\Seeders\PlaylistSeeder;
use Database\Seeders\ComposerSongSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            GenreSeeder::class,
            ArtistSeeder::class,
            AlbumSeeder::class,
            ComposerSeeder::class,
        ]);
        Song::factory(100)
        ->has(Link::factory())
        ->has(Contract::factory()->count(1))
        ->recycle([
            Album::all(),
            Artist::all(),
            Genre::all(),
            Composer::all(),
            Member::all()
        ])->create();
    }
}
