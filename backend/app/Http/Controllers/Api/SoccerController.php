<?php

namespace App\Http\Controllers\Api;

use App\Events\MatchUpdated;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSoccerRequest;
use App\Http\Requests\UpdateSoccerRequest;
use App\Http\Resources\SoccerResource;
use App\Models\League;
use App\Models\Soccer;
use App\Services\LeagueService;
use App\Services\SoccerService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SoccerController extends Controller
{
    public function __construct(protected SoccerService $service, protected LeagueService $leagueService) {}

    /**
     * @OA\Get(
     *     path="/api/soccers",
     *     summary="Get list of matches",
     *     tags={"Soccers"},
     *     @OA\Response(
     *         response=200,
     *         description="List of matches",
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Soccer")
     *          )
     *     )
     * )
     */
    public function index(): AnonymousResourceCollection
    {
        return SoccerResource::collection($this->service->getAll());
    }

    /**
     * @OA\Post(
     *     path="/api/soccers",
     *     summary="Create a new match",
     *     tags={"Soccers"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Soccer")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(ref="#/components/schemas/Soccer")
     *     )
     * )
     */
    public function store(StoreSoccerRequest $request): SoccerResource
    {
        return new SoccerResource($this->service->store($request->validated())->load(['homeTeam', 'awayTeam', 'week']));
    }

    /**
     * @OA\Get(
     *     path="/api/soccers/{id}",
     *     summary="Get a single match",
     *     tags={"Soccers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A single match",
     *         @OA\JsonContent(ref="#/components/schemas/Soccer")
     *     )
     * )
     */
    public function show(Soccer $match): SoccerResource
    {
        return new SoccerResource($this->service->getById($match->id));
    }

    /**
     * @OA\Put(
     *     path="/api/soccers/{id}",
     *     summary="Update a match",
     *     tags={"Soccers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Soccer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated",
     *         @OA\JsonContent(ref="#/components/schemas/Soccer")
     *     ),
     *     @OA\Response(
     *         response=403,
     *         description="Match result already set and cannot be edited",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Match result already set and cannot be edited.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Match not found",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="No query results for Soccer")
     *         )
     *     )
     * )
     */
    public function update(UpdateSoccerRequest $request, Soccer $match): SoccerResource
    {
        if (!is_null($match->home_goals) || !is_null($match->away_goals)) {
            return response()->json([
                'message' => 'Match result already set and cannot be edited.',
            ], 403);
        }

        $match = $this->service->update($match->id, $request->validated())->load(['homeTeam', 'awayTeam', 'week']);

        event(new MatchUpdated($match));

        return new SoccerResource($match);
    }

    /**
     * @OA\Delete(
     *     path="/api/soccers/{id}",
     *     summary="Delete a match",
     *     tags={"Soccers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="No Content")
     * )
     */
    public function destroy(Soccer $match)
    {
        $this->service->delete($match->id);

        return response()->noContent();
    }

    /**
     * @OA\Post(
     *     path="/api/soccers/{id}/simulate-all-unplayed",
     *     summary="Simulate all unplayed matches",
     *     tags={"Soccers"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="League ID to simulate",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Simulated league results",
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Week")
     *          )
     *     )
     * )
     */
    public function simulateAllUnPlayed(League $league): JsonResponse
    {
        $this->service->simulateAllUnplayed();

        return response()->json($this->leagueService->getLeagueTable($league->id));
    }
}
