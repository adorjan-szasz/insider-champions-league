<?php

namespace Database\Seeders;

use App\Models\League;
use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    public function __construct(protected ?int $leagueId = null) {}

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leagueId = $this->leagueId ?? League::first()->id;
        $league = League::findOrFail($leagueId);

        Team::factory()->count(4)->forLeague($league)->create();
    }
}
