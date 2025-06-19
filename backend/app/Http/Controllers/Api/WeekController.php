<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWeekRequest;
use App\Http\Requests\UpdateWeekRequest;
use App\Http\Resources\WeekResource;
use App\Models\Week;
use App\Services\WeekService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class WeekController extends Controller
{
    public function __construct(protected WeekService $service) {}

    /**
     * @OA\Get(
     *     path="/api/weeks",
     *     summary="Get list of weeks",
     *     tags={"Weeks"},
     *     @OA\Response(
     *         response=200,
     *         description="List of weeks",
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Week")
     *          )
     *     )
     * )
     */
    public function index(): AnonymousResourceCollection
    {
        return WeekResource::collection($this->service->getAllWithRelations());
    }

    /**
     * @OA\Post(
     *     path="/api/weeks",
     *     summary="Create a new week",
     *     tags={"Weeks"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Week")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(ref="#/components/schemas/Week")
     *     )
     * )
     */
    public function store(StoreWeekRequest $request): WeekResource
    {
        return new WeekResource($this->service->store($request->validated())->load(['league', 'matches']));
    }

    /**
     * @OA\Get(
     *     path="/api/weeks/{id}",
     *     summary="Get a single week",
     *     tags={"Weeks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A single week",
     *         @OA\JsonContent(ref="#/components/schemas/Week")
     *     )
     * )
     */
    public function show(Week $week): WeekResource
    {
        return new WeekResource($this->service->findWithRelations($week->id));
    }

    /**
     * @OA\Put(
     *     path="/api/weeks/{id}",
     *     summary="Update a week",
     *     tags={"Weeks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Week")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated",
     *         @OA\JsonContent(ref="#/components/schemas/Week")
     *     )
     * )
     */
    public function update(UpdateWeekRequest $request, Week $week): WeekResource
    {
        return new WeekResource($this->service->update($week->id, $request->validated())->load(['league', 'matches']));
    }

    /**
     * @OA\Delete(
     *     path="/api/weeks/{id}",
     *     summary="Delete a week",
     *     tags={"Weeks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="No Content")
     * )
     */
    public function destroy(Week $week)
    {
        $this->service->delete($week->id);

        return response()->noContent();
    }

    /**
     * @OA\Get(
     *     path="/api/leagues/{leagueId}/weeks/current/matches",
     *     summary="Get current week matches for a given league",
     *     description="Returns all matches for the current week in the specified league, including home and away teams.",
     *     operationId="getCurrentWeekMatches",
     *     tags={"Soccers"},
     *     @OA\Parameter(
     *         name="leagueId",
     *         in="path",
     *         description="League ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of matches with home and away teams",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="id", type="integer", example=12),
     *                 @OA\Property(property="week_id", type="integer", example=5),
     *                 @OA\Property(property="home_team_id", type="integer", example=3),
     *                 @OA\Property(property="away_team_id", type="integer", example=4),
     *                 @OA\Property(property="home_goals", type="integer", example=2),
     *                 @OA\Property(property="away_goals", type="integer", example=1),
     *                 @OA\Property(
     *                     property="home_team",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=3),
     *                     @OA\Property(property="name", type="string", example="Liverpool FC")
     *                 ),
     *                 @OA\Property(
     *                     property="away_team",
     *                     type="object",
     *                     @OA\Property(property="id", type="integer", example=4),
     *                     @OA\Property(property="name", type="string", example="Manchester United")
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="No current week found for this league"
     *     )
     * )
     */
    public function currentMatchesByLeague(int $leagueId): JsonResponse
    {
        $currentWeek = $this->service->getCurrentWeek($leagueId);

        if (!$currentWeek) {
            return response()->json([], 404);
        }

        $matches = $currentWeek->matches()->with(['homeTeam', 'awayTeam'])->get();

        return response()->json($matches);
    }

    /**
     * @OA\Post(
     *     path="/api/weeks/{id}/simulate-week",
     *     summary="Simulate a week in the league.",
     *     tags={"Weeks"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Week ID to simulate",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Simulated week results",
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Week")
     *          )
     *     )
     * )
     */
    public function simulateWeek(Week $week): JsonResponse
    {
        return response()->json($this->service->simulateWeek($week));
    }
}
