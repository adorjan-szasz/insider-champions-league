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

    public function test_generate_schedule_creates_matches()
    {
        $league = League::factory()->create();
        Team::factory()->count(4)->forLeague($league)->create();

        $service = resolve(LeagueService::class);
        $service->generateSchedule($league);

        $this->assertEquals(8, Soccer::where('league_id', $league->id)->count());
        $this->assertGreaterThan(0, Week::where('league_id', $league->id)->count());
    }

    public function test_simulate_matches_sets_scores()
    {
        $league = League::factory()->create();
        Team::factory()->count(4)->forLeague($league)->create();

        $service = resolve(LeagueService::class);
        $service->generateSchedule($league);
        $service->simulate($league);

        $matches = Soccer::where('league_id', $league->id)->get();
        $this->assertTrue($matches->every(fn ($m) => $m->home_score !== null && $m->away_score !== null));
    }
}
