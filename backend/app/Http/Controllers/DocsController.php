<?php

namespace App\Http\Controllers;

/**
 * @OA\Schema(
 *     schema="Team",
 *     type="object",
 *     title="Team",
 *     required={"id", "name", "strength"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="FC Swagger"),
 *     @OA\Property(property="strength", type="integer", example=80),
 *     @OA\Property(property="league_id", type="integer", example=2),
 *     @OA\Property(
 *         property="league",
 *         ref="#/components/schemas/League"
 *     ),
 *     @OA\Property(
 *         property="homeMatches",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Soccer")
 *     ),
 *     @OA\Property(
 *         property="awayMatches",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Soccer")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="League",
 *     type="object",
 *     title="League",
 *     required={"id", "name"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Premier League"),
 *     @OA\Property(
 *         property="teams",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Team")
 *     ),
 *     @OA\Property(
 *         property="weeks",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Week")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="Week",
 *     type="object",
 *     title="Week",
 *     required={"id", "week_number", "league_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="week_number", type="integer", example=1),
 *     @OA\Property(property="league_id", type="integer", example=1),
 *     @OA\Property(
 *         property="league",
 *         ref="#/components/schemas/League"
 *     ),
 *     @OA\Property(
 *         property="matches",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/Soccer")
 *     )
 * )
 *
 * @OA\Schema(
 *     schema="Soccer",
 *     type="object",
 *     title="Soccer",
 *     required={"id", "week_id", "home_team_id", "away_team_id"},
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="week_id", type="integer", example=1),
 *     @OA\Property(property="home_team_id", type="integer", example=1),
 *     @OA\Property(property="away_team_id", type="integer", example=2),
 *     @OA\Property(property="home_goals", type="integer", example=3, nullable=true),
 *     @OA\Property(property="away_goals", type="integer", example=2, nullable=true),
 *     @OA\Property(
 *          property="week",
 *          ref="#/components/schemas/Week"
 *      ),
 *     @OA\Property(
 *          property="homeTeam",
 *          ref="#/components/schemas/Team"
 *      ),
 *     @OA\Property(
 *          property="awayTeam",
 *          ref="#/components/schemas/Team"
 *      )
 * )
 */
class DocsController extends Controller
{

}
