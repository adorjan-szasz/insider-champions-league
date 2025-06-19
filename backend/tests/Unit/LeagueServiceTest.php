<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Team;
use App\Models\League;
use App\Models\Soccer;
use App\Models\Week;
use App\Services\LeagueService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeagueServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_generate_schedule_creates_matches_sets_scores()
    {
        $league = League::factory()->create();
        Team::factory()->count(4)->forLeague($league)->create();

        $service = resolve(LeagueService::class);
        $service->simulateLeague($league->id);

        $matches = Soccer::whereHas('week', function ($query) use ($league) {
            $query->where('league_id', $league->id);
        });

        $this->assertInstanceOf(League::class, $league);

        $this->assertEquals(12, $matches->count());

        $this->assertGreaterThan(0, Week::where('league_id', $league->id)->count());

        $this->assertTrue($matches->get()->every(fn ($m) => $m->home_goals !== null && $m->away_goals !== null));
    }
}
