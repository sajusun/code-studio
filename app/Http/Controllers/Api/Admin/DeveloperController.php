<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeveloperResource;
use App\Models\Developer;
use App\Modules\Media\Traits\HandlesMedia;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class DeveloperController extends Controller
{
    use ApiResponse, HandlesMedia;

    /**
     * GET /api/v1/admin/developers
     * Paginated list with optional filters: role, status, search.
     */
    public function index(Request $request)
    {
        $query = Developer::with('roles')->withCount('roles');

        // Filter by role slug
        if ($request->filled('role')) {
            $query->whereHas('roles', fn ($q) =>
                $q->where('slug', $request->input('role'))
            );
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Search by name or email
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $perPage = $request->integer('per_page', 15);
        $developers = $query->orderBy('sort_order')->orderByDesc('created_at')->paginate($perPage)->withQueryString();

        return DeveloperResource::collection($developers);
    }

    /**
     * POST /api/v1/admin/developers
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'             => 'required|string|max:150',
            'slug'             => 'nullable|string|max:180|unique:developers,slug',
            'email'            => 'nullable|email|max:255|unique:developers,email',
            'phone'            => 'nullable|string|max:30',
            'bio'              => 'nullable|string|max:2000',
            'github_url'       => 'nullable|url|max:255',
            'linkedin_url'     => 'nullable|url|max:255',
            'portfolio_url'    => 'nullable|url|max:255',
            'twitter_url'      => 'nullable|url|max:255',
            'skills'           => 'nullable|array',
            'skills.*'         => 'string|max:50',
            'experience_years' => 'nullable|integer|min:0|max:50',
            'status'           => 'nullable|in:published,private,draft',
            'sort_order'       => 'nullable|integer|min:0',
            'roles'            => 'nullable|array',
            'roles.*'          => 'integer|exists:developer_roles,id',
            'avatar'           => 'nullable|image|max:5120',
        ]);

        $validated['slug']   = $validated['slug'] ?? Str::slug($validated['name']);
        $validated['status'] = $validated['status'] ?? 'draft';

        $developer = Developer::create($validated);

        // Attach roles (many-to-many)
        if (!empty($validated['roles'])) {
            $developer->roles()->sync($validated['roles']);
        }

        // Upload avatar
        if ($request->hasFile('avatar')) {
            $this->uploadMedia($developer, $request->file('avatar'), 'avatars');
        }

        $developer->load('roles');

        return self::success('Developer created successfully', new DeveloperResource($developer), 201);
    }

    /**
     * GET /api/v1/admin/developers/{developer}
     */
    public function show(Developer $developer)
    {
        $developer->load('roles')->loadCount('roles');

        return self::success('Developer', new DeveloperResource($developer));
    }

    /**
     * PUT /api/v1/admin/developers/{developer}
     */
    public function update(Request $request, Developer $developer)
    {
        $validated = $request->validate([
            'name'             => 'sometimes|required|string|max:150',
            'slug'             => ['nullable', 'string', 'max:180', Rule::unique('developers', 'slug')->ignore($developer->id)],
            'email'            => ['nullable', 'email', 'max:255', Rule::unique('developers', 'email')->ignore($developer->id)],
            'phone'            => 'nullable|string|max:30',
            'bio'              => 'nullable|string|max:2000',
            'github_url'       => 'nullable|url|max:255',
            'linkedin_url'     => 'nullable|url|max:255',
            'portfolio_url'    => 'nullable|url|max:255',
            'twitter_url'      => 'nullable|url|max:255',
            'skills'           => 'nullable|array',
            'skills.*'         => 'string|max:50',
            'experience_years' => 'nullable|integer|min:0|max:50',
            'status'           => 'nullable|in:published,private,draft',
            'sort_order'       => 'nullable|integer|min:0',
            'roles'            => 'nullable|array',
            'roles.*'          => 'integer|exists:developer_roles,id',
            'avatar'           => 'nullable|image|max:5120',
        ]);

        $developer->update($validated);

        // Sync roles if provided
        if ($request->has('roles')) {
            $developer->roles()->sync($validated['roles'] ?? []);
        }

        // Update avatar if uploaded
        if ($request->hasFile('avatar')) {
            $this->updateMedia($developer, $request->file('avatar'), 'avatars');
        }

        $developer->load('roles')->loadCount('roles');

        return self::success('Developer updated successfully', new DeveloperResource($developer));
    }

    /**
     * PATCH /api/v1/admin/developers/{developer}/status
     * Quick status toggle without full update.
     */
    public function updateStatus(Request $request, Developer $developer)
    {
        $validated = $request->validate([
            'status' => 'required|in:published,private,draft',
        ]);

        $developer->update(['status' => $validated['status']]);

        return self::success(
            "Developer status changed to [{$validated['status']}]",
            ['id' => $developer->id, 'status' => $developer->status]
        );
    }

    /**
     * DELETE /api/v1/admin/developers/{developer}
     */
    public function destroy(Developer $developer)
    {
        // Detach roles (cascade on DB, but explicit for clarity)
        $developer->roles()->detach();
        // Delete media
        $developer->media()->delete();
        $developer->delete();

        return self::success('Developer deleted successfully');
    }
}
