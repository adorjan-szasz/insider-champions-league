<?php

namespace Tests\Unit;

use App\Models\Week;
use Tests\TestCase;
use App\Models\League;
use App\Services\WeekService;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WeekServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_week()
    {
        $league = League::factory()->create();

    $service = resolve(WeekService::class);

        $week = $service->store([
            'league_id' => $league->id,
            'week_number' => 1,
        ]);

        $this->assertInstanceOf(Week::class, $week);

        $this->assertEquals($league->id, $week->league_id);

        $this->assertEquals(1, $week->week_number);
    }
}
