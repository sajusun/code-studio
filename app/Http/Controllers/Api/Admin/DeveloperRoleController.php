<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeveloperRoleResource;
use App\Models\DeveloperRole;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DeveloperRoleController extends Controller
{
    use ApiResponse;

    /**
     * GET /api/v1/admin/developer-roles
     * List all developer role types with developer count.
     */
    public function index()
    {
        $roles = DeveloperRole::withCount('developers')
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return self::success('Developer roles', DeveloperRoleResource::collection($roles));
    }

    /**
     * POST /api/v1/admin/developer-roles
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100|unique:developer_roles,name',
            'slug'        => 'nullable|string|max:120|unique:developer_roles,slug',
            'color'       => 'nullable|string|max:20',
            'icon'        => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        $validated['slug'] = $validated['slug'] ?? Str::slug($validated['name']);

        $role = DeveloperRole::create($validated);

        return self::success('Developer role created', new DeveloperRoleResource($role), 201);
    }

    /**
     * GET /api/v1/admin/developer-roles/{role}
     */
    public function show(DeveloperRole $developerRole)
    {
        $developerRole->loadCount('developers');

        return self::success('Developer role', new DeveloperRoleResource($developerRole));
    }

    /**
     * PUT /api/v1/admin/developer-roles/{role}
     */
    public function update(Request $request, DeveloperRole $developerRole)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:100', Rule::unique('developer_roles', 'name')->ignore($developerRole->id)],
            'slug'        => ['nullable', 'string', 'max:120', Rule::unique('developer_roles', 'slug')->ignore($developerRole->id)],
            'color'       => 'nullable|string|max:20',
            'icon'        => 'nullable|string|max:50',
            'description' => 'nullable|string|max:500',
            'sort_order'  => 'nullable|integer|min:0',
        ]);

        if (isset($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['slug']);
        }

        $developerRole->update($validated);

        return self::success('Developer role updated', new DeveloperRoleResource($developerRole));
    }

    /**
     * DELETE /api/v1/admin/developer-roles/{role}
     */
    public function destroy(DeveloperRole $developerRole)
    {
        // Detach all developers before deletion (pivot rows cascade, but we inform)
        $developerRole->delete();

        return self::success('Developer role deleted');
    }
}
