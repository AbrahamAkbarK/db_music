<?php

namespace Database\Seeders;

use App\Models\Artist;
use App\Models\Contract;
use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ArtistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Artist::factory(20)
        ->has(Member::factory()->count(rand(3,5)))
        ->has(Contract::factory()->count(2))
        ->create();
    }
}
