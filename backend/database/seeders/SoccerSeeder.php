<?php

namespace Database\Seeders;

use App\Models\Soccer;
use App\Models\Team;
use App\Models\Week;
use Illuminate\Database\Seeder;

class SoccerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teams = Team::all();
        $weeks = Week::all();

        if ($teams->count() < 4) {
            throw new \Exception("Need at least 4 teams to seed matches.");
        }

        if ($weeks->count() < 4) {
            throw new \Exception("Need at least 4 weeks to seed matches.");
        }

        foreach ($weeks as $week) {
            $matchesData = [
                ['home' => $teams[0], 'away' => $teams[1]],
                ['home' => $teams[2], 'away' => $teams[3]],
            ];

            foreach ($matchesData as $match) {
                Soccer::factory()
                    ->withTeams($match['home'], $match['away'])
                    ->create(['week_id' => $week->id]);
            }
        }
    }
}
