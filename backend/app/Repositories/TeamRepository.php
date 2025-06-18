<?php

namespace App\Repositories;

use App\Models\Team;
use Illuminate\Database\Eloquent\Collection;

class TeamRepository extends BaseRepository
{
    public function __construct(Team $model)
    {
        parent::__construct($model);
    }

    public function getAllWithRelations(): Collection
    {
        return $this->model->with(['league', 'homeMatches', 'awayMatches'])->get();
    }

    public function findWithRelations($id): Team
    {
        return $this->model->with(['league', 'homeMatches', 'awayMatches'])->findOrFail($id);
    }

    public function getTeamsWithMatchesByLeagueId(int $leagueId): Collection
    {
        return $this->model->with(['homeMatches', 'awayMatches'])
            ->where('league_id', $leagueId)
            ->get();
    }
}
