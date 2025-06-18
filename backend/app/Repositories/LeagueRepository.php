<?php

namespace App\Repositories;

use App\Models\League;
use Illuminate\Database\Eloquent\Collection;

class LeagueRepository extends BaseRepository
{
    public function __construct(League $model)
    {
        parent::__construct($model);
    }

    public function getAllWithRelations(): Collection
    {
        return $this->model->with('weeks.matches')->get();
    }

    public function findWithRelations($id): League
    {
        return $this->model->with('weeks.matches')->findOrFail($id);
    }

    public function findWithTeams(int $id): League
    {
        return $this->model->with('teams')->findOrFail($id);
    }
}
