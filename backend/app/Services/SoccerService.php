<?php

namespace App\Services;

use App\Models\Soccer;
use App\Repositories\SoccerRepository;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property SoccerRepository $repository
 */
class SoccerService extends BaseService
{
    public function __construct(SoccerRepository $repository)
    {
        parent::__construct($repository);
    }

    public function getAll(): Collection
    {
        return $this->repository->getAllWithRelations();
    }

    public function getById($id): Soccer
    {
        return $this->repository->findWithRelations($id);
    }
}
