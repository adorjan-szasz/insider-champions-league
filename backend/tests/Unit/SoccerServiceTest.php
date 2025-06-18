<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Soccer;
use App\Models\Week;
use App\Models\League;
use App\Services\SoccerService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SoccerServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_simulate_match_sets_scores()
    {
        $league = League::factory()->create();
        $week = Week::factory()->forLeague($league)->create();
        $match = Soccer::factory()->create(['league_id' => $league->id, 'week_id' => $week->id]);

        $service = resolve(SoccerService::class);
        $service->simulate($match);

        $this->assertNotNull($match->fresh()->home_score);
        $this->assertNotNull($match->fresh()->away_score);
    }
}
