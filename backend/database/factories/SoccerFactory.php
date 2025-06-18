<?php

namespace Database\Factories;

use App\Models\Soccer;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Soccer>
 */
class SoccerFactory extends Factory
{
    protected $model = Soccer::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'home_goals' => $this->faker->numberBetween(0, 5),
            'away_goals' => $this->faker->numberBetween(0, 5),
        ];
    }

    /**
     * Set the home and away teams and assign a week within the home team's league.
     *
     * @param Team $home
     * @param Team $away
     * @return static
     *
     * @throws \InvalidArgumentException if home and away are the same team
     */
    public function withTeams(Team $home, Team $away): static
    {
        if ($home->id === $away->id) {
            throw new \InvalidArgumentException("Home and away teams must be different!");
        }

        if ($home->league_id !== $away->league_id) {
            throw new \InvalidArgumentException("Teams must belong to the same league!");
        }

        $week = $home->league->weeks()->inRandomOrder()->first();

        if (!$week) {
            throw new \RuntimeException("No weeks found for the league. Make sure weeks exist.");
        }

        return $this->state([
            'home_team_id' => $home->id,
            'away_team_id' => $away->id,
            'week_id' => $week->id,
        ]);
    }
}
