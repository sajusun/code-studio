<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeveloperResource;
use App\Http\Resources\DeveloperRoleResource;
use App\Models\Developer;
use App\Models\DeveloperRole;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class PublicDeveloperController extends Controller
{
    use ApiResponse;

    /**
     * GET /api/v1/public/developers
     * Returns only published developers for public showcase.
     */
    public function index(Request $request)
    {
        $query = Developer::published()->with(['roles', 'products' => fn ($q) => $q->where('is_active', true)]);

        // Filter by role slug (e.g. ?role=flutter-developer)
        if ($request->filled('role')) {
            $query->whereHas('roles', fn ($q) =>
                $q->where('slug', $request->input('role'))
            );
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->input('search') . '%');
        }

        $developers = $query->orderBy('sort_order')->orderBy('name')->get();

        return self::success('Developers', DeveloperResource::collection($developers));
    }

    /**
     * GET /api/v1/public/developers/{identifier}
     * Show a single published developer by ID or Slug.
     */
    public function show(string $identifier)
    {
        $developer = Developer::published()
            ->with(['roles', 'products' => fn ($q) => $q->where('is_active', true)])
            ->where(function ($q) use ($identifier) {
                if (is_numeric($identifier)) {
                    $q->where('id', (int) $identifier);
                } else {
                    $q->where('slug', $identifier);
                }
            })
            ->firstOrFail();

        return self::success('Developer', new DeveloperResource($developer));
    }

    /**
     * GET /api/v1/public/developer-roles
     * All developer role types (for filter UI on public site).
     */
    public function roles()
    {
        $roles = DeveloperRole::withCount([
                'developers as published_count' => fn ($q) => $q->where('status', 'published'),
            ])
            ->having('published_count', '>', 0)
            ->orderBy('sort_order')
            ->get();

        return self::success('Developer roles', DeveloperRoleResource::collection($roles));
    }
}
