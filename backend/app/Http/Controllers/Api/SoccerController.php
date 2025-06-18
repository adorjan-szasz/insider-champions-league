<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSoccerRequest;
use App\Http\Requests\UpdateSoccerRequest;
use App\Http\Resources\SoccerResource;
use App\Models\Soccer;
use App\Services\SoccerService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class SoccerController extends Controller
{
    public function __construct(protected SoccerService $service) {}

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
     *     )
     * )
     */
    public function update(UpdateSoccerRequest $request, Soccer $match): SoccerResource
    {
        return new SoccerResource($this->service->update($match->id, $request->validated())
            ->load(['homeTeam', 'awayTeam', 'week'])
        );
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
}
