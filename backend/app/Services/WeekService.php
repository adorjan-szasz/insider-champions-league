<?php

namespace App\Services;

use App\Models\Soccer;
use App\Models\Week;
use App\Repositories\SoccerRepository;
use App\Repositories\WeekRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property WeekRepository $repository
 */
class WeekService extends BaseService
{
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

    public function getWeeksByLeague(int $leagueId): Week
    {
        return $this->repository->getByLeagueWithMatches($leagueId);
    }

    public function updateMatchResult(int $matchId, int $homeScore, int $awayScore): Soccer
    {
        return $this->soccerRepository->updateMatchResult($matchId, $homeScore, $awayScore);
    }
}
