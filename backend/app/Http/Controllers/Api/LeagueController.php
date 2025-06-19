<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLeagueRequest;
use App\Http\Requests\UpdateLeagueRequest;
use App\Http\Resources\LeagueResource;
use App\Models\League;
use App\Services\LeagueService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class LeagueController extends Controller
{
    public function __construct(protected LeagueService $service) {}

    /**
     * @OA\Get(
     *     path="/api/leagues",
     *     summary="Get league standings table",
     *     tags={"Leagues"},
     *     @OA\Response(
     *         response=200,
     *         description="League standings",
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/League")
     *          )
     *     )
     * )
     */
    public function index(): AnonymousResourceCollection
    {
        return LeagueResource::collection($this->service->getAll());
    }

    /**
     * @OA\Post(
     *     path="/api/leagues",
     *     summary="Create a new league",
     *     tags={"Leagues"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/League")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="League created",
     *         @OA\JsonContent(ref="#/components/schemas/League")
     *     )
     * )
     */
    public function store(StoreLeagueRequest $request): LeagueResource
    {
        return new LeagueResource($this->service->store($request->validated()));
    }

    /**
     * @OA\Get(
     *     path="/api/leagues/{id}",
     *     summary="Get league standings",
     *     tags={"Leagues"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="League ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="League standings table",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="team", type="string"),
     *                 @OA\Property(property="played", type="integer"),
     *                 @OA\Property(property="won", type="integer"),
     *                 @OA\Property(property="drawn", type="integer"),
     *                 @OA\Property(property="lost", type="integer"),
     *                 @OA\Property(property="gf", type="integer"),
     *                 @OA\Property(property="ga", type="integer"),
     *                 @OA\Property(property="gd", type="integer"),
     *                 @OA\Property(property="points", type="integer")
     *             )
     *         )
     *     )
     * )
     */
    public function show(League $league): JsonResponse
    {
        return response()->json($this->service->getLeagueTable($league->id));
    }

    /**
     * @OA\Put(
     *     path="/api/leagues/{id}",
     *     summary="Update a league",
     *     tags={"Leagues"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="League ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string", example="Updated League Name")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="League updated",
     *         @OA\JsonContent(ref="#/components/schemas/League")
     *     )
     * )
     */
    public function update(UpdateLeagueRequest $request, League $league): LeagueResource
    {
        return new LeagueResource($this->service->update($league->id, $request->validated()));
    }

    /**
     * @OA\Delete(
     *     path="/api/leagues/{id}",
     *     summary="Delete a league",
     *     tags={"Leagues"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="League ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="No Content")
     * )
     */
    public function destroy(League $league)
    {
        $this->service->delete($league->id);

        return response()->noContent();
    }

    /**
     * @OA\Post(
     *     path="/api/leagues/{id}/simulate",
     *     summary="Simulate a league season",
     *     tags={"Leagues"},
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
    public function simulate(League $league): JsonResponse
    {
        return response()->json($this->service->simulateLeague($league->id));
    }
}
