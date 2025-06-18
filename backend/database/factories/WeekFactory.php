<?php

namespace Database\Factories;

use App\Models\League;
use App\Models\Week;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Week>
 */
class WeekFactory extends Factory
{
    protected $model = Week::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'league_id' => League::factory(),
            'week_number' => 1,
        ];
    }

    public function forLeague(League $league): static
    {
        return $this->state(fn () => [
            'league_id' => $league->id,
        ]);
    }
}
