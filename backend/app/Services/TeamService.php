<?php

namespace App\Services;

use App\Models\Team;
use App\Repositories\TeamRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property TeamRepository $repository
 */
class TeamService extends BaseService
{
    public function __construct(TeamRepository $repository)
    {
        parent::__construct($repository);
    }

    public function getAll(): Collection
    {
        return $this->repository->getAllWithRelations();
    }

    public function getById($id): Team
    {
        return $this->repository->findWithRelations($id);
    }
}
