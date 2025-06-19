<?php

namespace App\Services;

use App\Models\Soccer;
use App\Models\Week;
use App\Repositories\SoccerRepository;
use App\Repositories\WeekRepository;
use App\Traits\SimulatorHelper;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property WeekRepository $repository
 */
class WeekService extends BaseService
{
    use SimulatorHelper;

    public function __construct(
        WeekRepository $repository,
        protected SoccerRepository $soccerRepository
    )
    {
        parent::__construct($repository);
    }

    public function getAllWithRelations(): Collection
    {
        return $this->repository->getAllWithRelations();
    }

    public function findWithRelations($id): Week
    {
        return $this->repository->findWithRelations($id);
    }

    public function getWeeksByLeague(int $leagueId): Collection
    {
        return $this->repository->getByLeagueWithMatches($leagueId);
    }

    public function updateMatchResult(int $matchId, int $homeScore, int $awayScore): Soccer
    {
        return $this->soccerRepository->updateMatchResult($matchId, $homeScore, $awayScore);
    }

    /**
     * Get the current week (first week with un-played matches).
     */
    public function getCurrentWeek(int $leagueId): ?Week
    {
        $week = Week::where('league_id', $leagueId)
            ->whereHas('matches', function ($query) {
                $query->whereNull('home_goals')->orWhereNull('away_goals');
            })
            ->orderBy('week_number')
            ->first();

        if ($week) {
            return $week;
        }

        return Week::where('league_id', $leagueId)
            ->orderByDesc('week_number')
            ->first();
    }

    private function getWeeksWithUnPlayedMatches()
    {
        return Week::whereHas('soccers', function ($query) {
            $query->whereNull('home_goals')->orWhereNull('away_goals');
        })
            ->orderBy('week_number');
    }

    public function simulateWeek(Week $week): array
    {
        $matches = $week->matches()->with(['homeTeam', 'awayTeam'])->get();

        foreach ($matches as $match) {
            if (is_null($match->home_goals) || is_null($match->away_goals)) {
                $homeStrength = $match->homeTeam->strength ?? 50;
                $awayStrength = $match->awayTeam->strength ?? 50;

                $total = $homeStrength + $awayStrength;
                $homeProb = $homeStrength / $total;
                $awayProb = $awayStrength / $total;

                $match->update([
                    'home_goals' => $this->randomGoals($homeProb),
                    'away_goals' => $this->randomGoals($awayProb),
                ]);
            }
        }

        return $week->load('soccers')->toArray();
    }
}
