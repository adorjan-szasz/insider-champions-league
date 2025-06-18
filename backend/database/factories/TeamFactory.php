<?php

namespace Database\Factories;

use App\Models\League;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Team>
 */
class TeamFactory extends Factory
{
    protected $model = Team::class;

    // Fixed cities for Premier League flavour
    protected static $premierLeagueCities = [
        'London', 'Manchester', 'Liverpool', 'Birmingham', 'Leeds',
        'Newcastle', 'Sheffield', 'Bristol', 'Cardiff', 'Nottingham',
    ];

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $city = $this->faker->unique()->randomElement(self::$premierLeagueCities);

        return [
            'league_id' => League::factory(),
            'name' => $city . ' FC',
            'strength' => $this->faker->numberBetween(30, 100),
        ];
    }

    /**
     * Set the league for the team factory.
     *
     * @param League $league
     *
     * @return static
     */
    public function forLeague(League $league): static
    {
        return $this->state(fn() => [
            'league_id' => $league->id,
        ]);
    }
}
