<?php

namespace Database\Seeders;

use App\Models\Composer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ComposerSongSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       \App\Models\Composer::find(1)->songs()->attach([
        1 => ['role' => 'primary_composer'],
        2 => ['role' => 'arranger'],
    ]);
    }
}
