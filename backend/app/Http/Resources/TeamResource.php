<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeamResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'       => $this->id,
            'name'     => $this->name,
            'strength' => $this->strength,
            'league'   => new LeagueResource($this->whenLoaded('league')),
            'home_matches' => SoccerResource::collection($this->whenLoaded('homeMatches')),
            'away_matches' => SoccerResource::collection($this->whenLoaded('awayMatches')),
        ];
    }
}
