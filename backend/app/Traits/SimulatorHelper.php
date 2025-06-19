<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;

trait SimulatorHelper
{
    public static function generateSchedule(Collection $teams): array
    {
        $matches = [];
        $count = $teams->count();

        for ($i = 0; $i < $count - 1; $i++) {
            for ($j = $i + 1; $j < $count; $j++) {
                $matches[] = [$teams[$i], $teams[$j]];
            }
        }

        $reverseMatches = array_map(fn($pair) => [$pair[1], $pair[0]], $matches);

        $allMatches = array_merge($matches, $reverseMatches);

        shuffle($allMatches);

        return array_chunk($allMatches, 2);
    }

    public static function randomGoals(float $probability): int
    {
        $goals = 0;

        for ($i = 0; $i < 5; $i++) {
            if (mt_rand() / mt_getrandmax() < $probability) {
                $goals++;
            }
        }

        return $goals;
    }
}
