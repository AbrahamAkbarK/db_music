<?php

namespace Database\Factories;

use App\Models\Song;
use App\Models\Artist;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Contract>
 */
class ContractFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('-3 year', 'now');
        $contractTypes = ['Flat', 'Royalty Base'];
        $statuses = ['Draft', 'Active', 'Expired'];

        return [
            'contract_number' => fake()->unique()->bothify('CTR-####-????'),
            'contract_type' => fake()->randomElement($contractTypes),
            'amount' => fake()->randomFloat(2, 500, 100000),
            'status' => fake()->randomElement($statuses),
            'start_date' => $startDate,
            // 75% chance of having an end date, 25% chance of being null
            'end_date' => fake()->dateTimeBetween($startDate, '+3 years'),
        ];
    }

    /**
     * Configure the model factory.
     * By default, let's make new contracts belong to a new Song.
     */
    public function configure(): static
    {
        // This sets a default relationship if one isn't specified when calling the factory
        return $this->forSong();
    }

    /**
     * STATE: Indicate that the contract belongs to a Song.
     */
    public function forSong(): static
    {
        return $this->state(fn (array $attributes) => [
            'contractable_type' => Song::class,
            'contractable_id' => Song::factory(),
        ]);
    }

    /**
     * STATE: Indicate that the contract belongs to an Artist.
     */
    public function forArtist(): static
    {
        return $this->state(fn (array $attributes) => [
            'contractable_type' => Artist::class,
            'contractable_id' => Artist::factory(),
        ]);
    }


}
