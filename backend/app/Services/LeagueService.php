<?php

namespace App\Services;

use App\Models\League;
use App\Repositories\LeagueRepository;
use App\Repositories\SoccerRepository;
use App\Repositories\TeamRepository;
use App\Repositories\WeekRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

/**
 * @property LeagueRepository $repository
 */
class LeagueService extends BaseService
{
    public function __construct(
        LeagueRepository $repository,
        protected TeamRepository $teamRepository,
        protected SoccerRepository $soccerRepository,
        protected WeekRepository $weekRepository,
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

    public function getLeagueTable(int $leagueId): Collection
    {
        $teams = $this->teamRepository->getTeamsWithMatchesByLeagueId($leagueId);

        return $teams->map(function ($team) {
            $matches = $team->homeMatches->merge($team->awayMatches);
            $stats = [
                'team' => $team->name,
                'played' => 0,
                'won' => 0,
                'drawn' => 0,
                'lost' => 0,
                'gf' => 0,
                'ga' => 0,
            ];

            foreach ($matches as $match) {
                // If no scores yet, skip
                if ($match->home_score === null || $match->away_score === null) continue;

                $stats['played']++;
                $isHome = $match->home_team_id === $team->id;

                $gf = $isHome ? $match->home_score : $match->away_score;
                $ga = $isHome ? $match->away_score : $match->home_score;
                $stats['gf'] += $gf;
                $stats['ga'] += $ga;

                if ($gf > $ga) $stats['won']++;
                elseif ($gf < $ga) $stats['lost']++;
                else $stats['drawn']++;
            }

            $stats['gd'] = $stats['gf'] - $stats['ga'];
            $stats['points'] = $stats['won'] * 3 + $stats['drawn'];

            return $stats;
        })->sortByDesc('points')->values();
    }

    public function simulateLeague(int $leagueId): array
    {
        $league = $this->repository->findWithTeams($leagueId);
        $teams = $league->teams;

        $schedule = $this->generateSchedule($teams);

        DB::transaction(function () use ($league, $schedule) {
            foreach ($schedule as $weekNumber => $matches) {
                $week = $this->weekRepository->createWeek([
                    'league_id' => $league->id,
                    'number' => $weekNumber + 1
                ]);

                foreach ($matches as [$home, $away]) {
                    $this->soccerRepository->createMatch([
                        'week_id' => $week->id,
                        'home_team_id' => $home->id,
                        'away_team_id' => $away->id,
                        'home_score' => rand(0, 5),
                        'away_score' => rand(0, 5),
                    ]);
                }
            }
        });

        return $this->weekRepository
            ->getByLeagueWithMatches($leagueId)
            ->toArray();
    }

    public function generateSchedule($teams): array
    {
        $matches = [];
        $count = count($teams);

        for ($i = 0; $i < $count - 1; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $matches[] = [$teams[$i], $teams[$j]];
            }
        }

        shuffle($matches);

        return array_chunk($matches, 2);
    }
}
