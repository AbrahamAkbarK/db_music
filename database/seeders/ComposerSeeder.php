<?php

namespace Database\Seeders;

use App\Models\Composer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ComposerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $composers = [
            [
                'name' => 'Ludwig van Beethoven',
                'nationality' => 'German',
                'birth_date' => '1770-12-16',
                'death_date' => '1827-03-26',
                'bio' => 'German composer and pianist, widely regarded as one of the greatest composers in the history of Western music.',
                'image_url' => 'https://example.com/beethoven.jpg',
                'email' => null,
                'phone' => null,
                'address' => null,
                'gender' => 'Male',
            ],
            [
                'name' => 'Wolfgang Amadeus Mozart',
                'nationality' => 'Austrian',
                'birth_date' => '1756-01-27',
                'death_date' => '1791-12-05',
                'bio' => 'Prolific and influential composer of the Classical era.',
                'image_url' => 'https://example.com/mozart.jpg',
                'email' => null,
                'phone' => null,
                'address' => null,
                'gender' => 'Male',
            ],
            [
                'name' => 'Johann Sebastian Bach',
                'nationality' => 'German',
                'birth_date' => '1685-03-31',
                'death_date' => '1750-07-28',
                'bio' => 'German composer and musician of the Baroque period.',
                'image_url' => 'https://example.com/bach.jpg',
                'email' => null,
                'phone' => null,
                'address' => null,
                'gender' => 'Male',
            ],
            [
                'name' => 'Hans Zimmer',
                'nationality' => 'German',
                'birth_date' => '1957-09-12',
                'death_date' => null,
                'bio' => 'German film score composer and record producer.',
                'image_url' => 'https://example.com/zimmer.jpg',
                'email' => 'info@hanszimmer.com',
                'phone' => '+49-89-123456',
                'address' => 'Remote Studios, Los Angeles, CA 90028, USA',
                'gender' => 'Male',
            ],
            [
                'name' => 'John Williams',
                'nationality' => 'American',
                'birth_date' => '1932-02-08',
                'death_date' => null,
                'bio' => 'American composer, conductor, and pianist, known for film scores.',
                'image_url' => 'https://example.com/williams.jpg',
                'email' => 'contact@johnwilliams.com',
                'phone' => '+1-310-555-0123',
                'address' => 'Beverly Hills, CA 90210, USA',
                'gender' => 'Male',
            ],
        ];
        foreach ($composers as $composerData) {
            Composer::create($composerData);
        }
    }
}
