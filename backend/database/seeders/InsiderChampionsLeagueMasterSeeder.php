<?php

namespace Database\Seeders;

use App\Models\League;
use Illuminate\Database\Seeder;

class InsiderChampionsLeagueMasterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->call(LeagueSeeder::class);

        $league = League::firstOrFail();

        (new TeamSeeder($league->id))->run();
        (new WeekSeeder($league->id))->run();

        $this->call(SoccerSeeder::class);
    }
}
