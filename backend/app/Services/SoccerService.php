<?php

namespace App\Services;

use App\Models\Soccer;
use App\Repositories\SoccerRepository;
use App\Traits\SimulatorHelper;
use Illuminate\Database\Eloquent\Collection;

/**
 * @property SoccerRepository $repository
 */
class SoccerService extends BaseService
{
    use SimulatorHelper;

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

    public function simulateAllUnplayed(): void
    {
        $matches = Soccer::with(['homeTeam', 'awayTeam'])
            ->whereNull('home_goals')
            ->whereNull('away_goals')
            ->get();

        foreach ($matches as $match) {
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
}
