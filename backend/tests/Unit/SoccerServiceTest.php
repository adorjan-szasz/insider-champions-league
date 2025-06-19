<?php

namespace Tests\Unit;

use App\Models\Team;
use Tests\TestCase;
use App\Models\Soccer;
use App\Models\Week;
use App\Models\League;
use App\Services\SoccerService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SoccerServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_match()
    {
        $league = League::factory()->create();
        $week = Week::factory()->forLeague($league)->create();
        $home = Team::factory()->forLeague($league)->create();
        $away = Team::factory()->forLeague($league)->create();

        $service = resolve(SoccerService::class);

        $match = $service->store([
            'home_team_id' => $home->id,
            'away_team_id' => $away->id,
            'home_goals'   => 3,
            'away_goals'   => 0,
            'week_id'      => $week->id,
        ]);

        $this->assertInstanceOf(Soccer::class, $match);

        $this->assertEquals($home->id, $match->home_team_id);

        $this->assertEquals($away->id, $match->away_team_id);

        $this->assertEquals(3, $match->home_goals);

        $this->assertEquals(0, $match->away_goals);

        $this->assertEquals($week->id, $match->week_id);
    }
}
