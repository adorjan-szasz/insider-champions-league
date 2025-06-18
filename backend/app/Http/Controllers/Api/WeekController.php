<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreWeekRequest;
use App\Http\Requests\UpdateWeekRequest;
use App\Http\Resources\WeekResource;
use App\Models\Week;
use App\Services\WeekService;
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
}
