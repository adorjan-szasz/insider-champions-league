<?php

namespace App\Repositories;

use App\Models\Soccer;
use Illuminate\Database\Eloquent\Collection;

class SoccerRepository extends BaseRepository
{
    public function __construct(Soccer $model)
    {
        parent::__construct($model);
    }

    public function getAllWithRelations(): Collection
    {
        return $this->model->with(['homeTeam', 'awayTeam', 'week'])->get();
    }

    public function findWithRelations($id): Soccer
    {
        return $this->model->with(['homeTeam', 'awayTeam', 'week'])->findOrFail($id);
    }

    public function createMatch(array $data): Soccer
    {
        return $this->model->create($data);
    }

    public function updateMatchResult(int $matchId, int $homeScore, int $awayScore): Soccer
    {
        $match = $this->model->findOrFail($matchId);

        $match->update([
            'home_goals' => $homeScore,
            'away_goals' => $awayScore,
        ]);

        return $match;
    }
}
