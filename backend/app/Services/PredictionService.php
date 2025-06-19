<?php

namespace App\Services;

use Illuminate\Support\Collection;

class PredictionService
{
    public function predictChampionChances(Collection $standings): array
    {
        $total = $standings->sum(fn($t) => $t['points'] * 3 + $t['goal_difference']);

        return $standings->map(function ($team) use ($total) {
            $weight = ($team['points'] * 3 + $team['goal_difference']) ?: 1;
            $chance = $total ? round(($weight / $total) * 100, 2) : 0;

            return [
                'team' => $team['team'],
                'chance' => $chance,
            ];
        })->sortByDesc('chance')->values()->toArray();
    }
}
