<?php

namespace App\Services;

use App\Models\League;
use App\Repositories\LeagueRepository;
use App\Repositories\SoccerRepository;
use App\Repositories\TeamRepository;
use App\Repositories\WeekRepository;
use App\Traits\SimulatorHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * @property LeagueRepository $repository
 */
class LeagueService extends BaseService
{
    use SimulatorHelper;

    public function __construct(
        LeagueRepository $repository,
        protected TeamRepository $teamRepository,
        protected SoccerRepository $soccerRepository,
        protected WeekRepository $weekRepository,
        protected PredictionService $predictionService,
    )
    {
        parent::__construct($repository);
    }

    public function getAll(): Collection
    {
        return $this->repository->getAllWithRelations();
    }

    public function getById($id): League
    {
        return $this->repository->findWithRelations($id);
    }

    public function getLeagueTable(int $leagueId): array
    {
        $league = League::with('weeks')->findOrFail($leagueId);

        $teams = $this->teamRepository->getTeamsWithMatchesByLeagueId($leagueId);

        $standings =  $teams->map(function ($team) use ($leagueId) {
            $matches = $team->homeMatches->merge($team->awayMatches);

            $stats = [
                'leagueId' => $leagueId,
                'id' => $team->id,
                'team' => $team->name,
                'played' => 0,
                'won' => 0,
                'drawn' => 0,
                'lost' => 0,
                'goals_for' => 0,
                'goals_against' => 0,
            ];

            foreach ($matches as $match) {
                if ($match->home_goals === null || $match->away_goals === null) continue;

                $stats['played']++;
                $isHome = $match->home_team_id === $team->id;

                $gf = $isHome ? $match->home_goals : $match->away_goals;
                $ga = $isHome ? $match->away_goals : $match->home_goals;

                $stats['goals_for'] += $gf;
                $stats['goals_against'] += $ga;

                if ($gf > $ga) {
                    $stats['won']++;
                } elseif ($gf < $ga) {
                    $stats['lost']++;
                } else {
                    $stats['drawn']++;
                }
            }

            $stats['goal_difference'] = $stats['goals_for'] - $stats['goals_against'];
            $stats['points'] = $stats['won'] * 3 + $stats['drawn'];

            return $stats;
        })->sortByDesc(fn ($team) => [
                $team['points'],
                $team['goal_difference'],
                $team['goals_for'],
            ])->values();

        $predictions = $this->predictionService->predictChampionChances($standings);

        $predictionMap = collect($predictions)->keyBy('team');

        $standingsWithChances = $standings->map(function ($teamStats) use ($predictionMap) {
            $chance = $predictionMap[$teamStats['team']]['chance'] ?? 0;
            $teamStats['champion_chance'] = $chance;
            return $teamStats;
        });

        return [
            'league' => $league,
            'standings' => $standingsWithChances,
            'weeks' => $league->weeks,
        ];
    }

    public function simulateLeague(int $leagueId): array
    {
        $league = $this->repository->findWithTeams($leagueId);
        $teams = $league->teams;

        $schedule = $this->generateSchedule($teams);

        DB::transaction(function () use ($league, $schedule) {
            $league->weeks()->delete();

            foreach ($schedule as $weekNumber => $matches) {
                $week = $this->weekRepository->createWeek([
                    'league_id'   => $league->id,
                    'week_number' => $weekNumber + 1,
                ]);

                foreach ($matches as [$home, $away]) {
                    $this->soccerRepository->createMatch([
                        'week_id'       => $week->id,
                        'home_team_id'  => $home->id,
                        'away_team_id'  => $away->id,
                        'home_goals'    => rand(0, 5),
                        'away_goals'    => rand(0, 5),
                    ]);
                }
            }
        });

        return $this->weekRepository
            ->getByLeagueWithMatches($leagueId)
            ->toArray();
    }
}
