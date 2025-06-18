<?php

namespace Database\Seeders;

use App\Models\League;
use App\Models\Week;
use Illuminate\Database\Seeder;

class WeekSeeder extends Seeder
{
    public function __construct(protected ?int $leagueId = null) {}

    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $leagueId = $this->leagueId ?? League::first()->id;
        $league = League::find($leagueId);

        foreach (range(1, 4) as $number) {
            Week::factory()->forLeague($league)->create(['week_number' => $number]);
        }
    }
}
