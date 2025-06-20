<?php

namespace Database\Factories;

use App\Models\League;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Factory>
 */
class LeagueFactory extends Factory
{
    protected $model = League::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->word . ' League',
        ];
    }
}
