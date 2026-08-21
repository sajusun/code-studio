<?php

namespace App\Modules\Guest\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Guest\Http\Requests\StoreGuestRequest;
use App\Modules\Guest\Http\Requests\UpdateGuestRequest;
use App\Modules\Guest\Http\Resources\GuestResource;
use App\Modules\Guest\Models\Guest;
use App\Modules\Guest\Repositories\Contracts\GuestRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class GuestController extends Controller
{
    public function __construct(
        private readonly GuestRepositoryInterface $guests,
    ) {}

    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Guest::query();

        if ($q = $request->query('q')) {
            $query->where(function ($sub) use ($q) {
                $sub->where('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%");
            });
        }

        return GuestResource::collection(
            $query->orderBy('last_name')->orderBy('first_name')->paginate(20)
        );
    }

    public function store(StoreGuestRequest $request): GuestResource
    {
        $guest = $this->guests->create($request->validated());

        return new GuestResource($guest);
    }

    public function show(int $guest): GuestResource
    {
        return new GuestResource($this->guests->findByIdOrFail($guest));
    }

    public function update(UpdateGuestRequest $request, int $guest): GuestResource
    {
        $updated = $this->guests->update($guest, $request->validated());

        return new GuestResource($updated);
    }
}
