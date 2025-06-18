<?php

namespace Tests\Unit;

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

        $week = $service->create($league->id, 1);

        $this->assertEquals($league->id, $week->league_id);
        $this->assertEquals(1, $week->number);
    }
}
