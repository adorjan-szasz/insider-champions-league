<?php

namespace App\Listeners;

use App\Events\MatchUpdated;
use App\Services\LeagueService;

class RecalculateLeagueStandings
{
    public function __construct(protected LeagueService $leagueService) {}

    public function handle(MatchUpdated $event): void
    {
        $league = $event->match->week->league ?? null;

        if ($league) {
            $this->leagueService->getLeagueTable($league->id);
        }
    }
}
