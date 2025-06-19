<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Team;
use App\Models\League;
use App\Services\TeamService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TeamServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_team()
    {
        $league = League::factory()->create();
        $service = resolve(TeamService::class);

        $team = $service->store([
            'name' => 'Team A',
            'strength' => 78,
            'league_id' => $league->id,
        ]);

        $this->assertInstanceOf(Team::class, $team);

        $this->assertEquals('Team A', $team->name);

        $this->assertEquals(78, $team->strength);

        $this->assertEquals($league->id, $team->league_id);
    }
}
