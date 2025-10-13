<?php

namespace Database\Factories;

use App\Models\Artist;
use App\Models\Member;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Member>
 */
class MemberFactory extends Factory
{
    protected $model = Member::class;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>$this->faker->name(),
            'phone'=>$this->faker->unique()->phoneNumber(),
            'email'=>$this->faker->unique()->safeEmail(),
            'artist_id'=>Artist::factory()
        ];
    }
}
