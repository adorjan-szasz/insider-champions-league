<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeamRequest;
use App\Http\Requests\UpdateTeamRequest;
use App\Http\Resources\TeamResource;
use App\Models\Team;
use App\Services\TeamService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class TeamController extends Controller
{
    public function __construct(protected TeamService $service) {}

    /**
     * @OA\Get(
     *     path="/api/teams",
     *     summary="Get list of teams",
     *     tags={"Teams"},
     *     @OA\Response(
     *         response=200,
     *         description="List of teams",
     *         @OA\JsonContent(
     *              type="array",
     *              @OA\Items(ref="#/components/schemas/Team")
     *          )
     *     )
     * )
     */
    public function index(): AnonymousResourceCollection
    {
        return TeamResource::collection($this->service->getAll());
    }

    /**
     * @OA\Post(
     *     path="/api/teams",
     *     summary="Create a new team",
     *     tags={"Teams"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Team")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Created",
     *         @OA\JsonContent(ref="#/components/schemas/Team")
     *     )
     * )
     */
    public function store(StoreTeamRequest $request): TeamResource
    {
        return new TeamResource($this->service->store($request->validated())->load(['league']));
    }

    /**
     * @OA\Get(
     *     path="/api/teams/{id}",
     *     summary="Get a single team",
     *     tags={"Teams"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="A single team",
     *         @OA\JsonContent(ref="#/components/schemas/Team")
     *     )
     * )
     */
    public function show(Team $team): TeamResource
    {
        return new TeamResource($this->service->getById($team->id));
    }

    /**
     * @OA\Put(
     *     path="/api/teams/{id}",
     *     summary="Update a team",
     *     tags={"Teams"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Team")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Updated",
     *         @OA\JsonContent(ref="#/components/schemas/Team")
     *     )
     * )
     */
    public function update(UpdateTeamRequest $request, Team $team): TeamResource
    {
        return new TeamResource($this->service->update($team->id, $request->validated())
            ->load(['league', 'homeMatches', 'awayMatches'])
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/teams/{id}",
     *     summary="Delete a team",
     *     tags={"Teams"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="No Content")
     * )
     */
    public function destroy(Team $team)
    {
        $this->service->delete($team->id);

        return response()->noContent();
    }
}
