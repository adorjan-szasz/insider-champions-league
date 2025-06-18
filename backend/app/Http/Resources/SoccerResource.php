<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SoccerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'           => $this->id,
            'home_team'    => new TeamResource($this->whenLoaded('homeTeam')),
            'away_team'    => new TeamResource($this->whenLoaded('awayTeam')),
            'home_goals'   => $this->home_goals,
            'away_goals'   => $this->away_goals,
            'week'         => new WeekResource($this->whenLoaded('week')),
        ];
    }
}
