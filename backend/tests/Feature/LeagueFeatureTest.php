<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use App\Models\Team;
use App\Models\League;
use App\Models\Week;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeagueFeatureTest extends TestCase
{
    use RefreshDatabase;

    public function test_using_test_database()
    {
        $dbName = DB::getDatabaseName();
        $this->assertEquals('insider_champions_league_test', $dbName);
    }

    public function test_league_can_be_created()
    {
        $response = $this->postJson('/api/leagues', [
            'name' => 'Test League',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'name']]);

        $this->assertDatabaseHas('leagues', ['name' => 'Test League']);
    }

    public function test_teams_can_be_created_for_league()
    {
        $league = League::factory()->create();
        $team = Team::factory()->forLeague($league)->create();

        $this->assertDatabaseHas('teams', [
            'id' => $team->id,
            'league_id' => $league->id,
        ]);
    }

    public function test_league_can_generate_matches()
    {
        $league = League::factory()->create();
        Team::factory()->count(4)->forLeague($league)->create();

        $response = $this->postJson("/api/leagues/{$league->id}/simulate");

        $response->assertStatus(200);
        $this->assertDatabaseCount('soccers', 12);
        $this->assertDatabaseHas('weeks', ['league_id' => $league->id]);
    }

    public function test_league_table_can_be_retrieved()
    {
        $league = League::factory()->create();
        Team::factory()->count(4)->forLeague($league)->create();

        foreach (range(1, 6) as $number) {
            Week::factory()->forLeague($league)->create(['week_number' => $number]);
        }

        $this->postJson("/api/leagues/{$league->id}/simulate");

        $response = $this->getJson("/api/leagues/{$league->id}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'standings' => [
                    '*' => [
                        'id',
                        'team',
                        'played',
                        'won',
                        'drawn',
                        'lost',
                        'goals_for',
                        'goals_against',
                        'goal_difference',
                        'points',
                        'champion_chance',
                    ],
                ],
            ]
        );
    }
}
