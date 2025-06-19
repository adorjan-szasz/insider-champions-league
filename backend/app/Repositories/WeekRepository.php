<?php

namespace App\Repositories;

use App\Models\Week;
use Illuminate\Database\Eloquent\Collection;

class WeekRepository extends BaseRepository
{
    public function __construct(Week $model)
    {
        parent::__construct($model);
    }

    public function getAllWithRelations(): Collection
    {
        return $this->model->with(['league', 'matches'])->get();
    }

    public function findWithRelations($id): Week
    {
        return $this->model->with(['league', 'matches'])->findOrFail($id);
    }

    public function createWeek(array $data): Week
    {
        return $this->model->create($data);
    }

    public function getByLeagueWithMatches(int $leagueId): Collection
    {
        return $this->model
            ->with('matches.homeTeam', 'matches.awayTeam')
            ->where('league_id', $leagueId)
            ->orderBy('week_number')
            ->get();
    }
}
