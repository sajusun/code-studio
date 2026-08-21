<?php

namespace App\Modules\Stay\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Stay\Http\Resources\StayResource;
use App\Modules\Stay\Models\Stay;
use App\Modules\Stay\Repositories\Contracts\StayRepositoryInterface;
use App\Modules\Stay\Services\StayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StayController extends Controller
{
    public function __construct(
        private readonly StayService $stayService,
        private readonly StayRepositoryInterface $stays,
    ) {}

    public function index(): AnonymousResourceCollection
    {
        return StayResource::collection(
            Stay::query()
                ->with(['reservation', 'room.roomType', 'guest', 'invoice'])
                ->latest()
                ->paginate(20)
        );
    }

    public function show(int $stay): StayResource
    {
        return new StayResource($this->stays->findByIdOrFail($stay));
    }

    public function checkIn(int $reservation): JsonResponse
    {
        $stay = $this->stayService->checkIn($reservation);

        return (new StayResource($stay))
            ->response()
            ->setStatusCode(201);
    }

    public function checkOut(int $stay): StayResource
    {
        $updated = $this->stayService->checkOut($stay);

        return new StayResource($updated);
    }
}
