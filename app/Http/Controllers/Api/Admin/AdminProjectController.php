<?php

namespace App\Http\Controllers\Api\Admin;

use App\Events\NewProjectMessageEvent;
use App\Http\Controllers\Controller;
use App\Models\ClientProject;
use App\Models\Developer;
use App\Models\ProjectMessage;
use App\Models\ProjectMilestone;
use App\Models\User;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminProjectController extends Controller
{
    use ApiResponse;

    /**
     * List all projects across all clients.
     */
    public function index(Request $request): JsonResponse
    {
        $projects = ClientProject::with(['client', 'milestones', 'developers.roles'])
            ->latest()
            ->paginate($request->integer('per_page', 20));

        return self::success('All Client Projects', $projects);
    }

    /**
     * Create a new project and auto-create client user if not exists.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'client_email'      => ['required', 'email', 'max:190'],
            'client_name'       => ['required', 'string', 'max:190'],
            'client_password'   => ['nullable', 'string', 'min:6'],
            'title'             => ['required', 'string', 'max:190'],
            'description'       => ['nullable', 'string'],
            'platform'          => ['nullable', 'string', 'max:100'],
            'budget'            => ['nullable', 'numeric'],
            'currency'          => ['nullable', 'string', 'max:10'],
            'status'            => ['nullable', 'in:discovery,in_progress,review,completed,on_hold'],
            'progress_percent'  => ['nullable', 'integer', 'min:0', 'max:100'],
            'start_date'        => ['nullable', 'date'],
            'delivery_deadline' => ['nullable', 'date'],
            'staging_url'       => ['nullable', 'url', 'max:500'],
            'github_repo_url'   => ['nullable', 'url', 'max:500'],
            'apk_build_url'     => ['nullable', 'url', 'max:500'],
            'figma_url'         => ['nullable', 'url', 'max:500'],
            'developer_ids'     => ['nullable', 'array'],
            'developer_ids.*'   => ['exists:developers,id'],
        ]);

        // Find or create client user
        $password = $validated['client_password'] ?? 'client' . Str::random(6);
        $user = User::firstOrCreate(
            ['email' => strtolower($validated['client_email'])],
            [
                'name'     => $validated['client_name'],
                'password' => Hash::make($password),
                'role'     => 'client',
            ]
        );

        $project = ClientProject::create([
            'user_id'           => $user->id,
            'title'             => $validated['title'],
            'description'       => $validated['description'] ?? null,
            'platform'          => $validated['platform'] ?? 'Custom Software Build',
            'budget'            => $validated['budget'] ?? null,
            'currency'          => $validated['currency'] ?? 'USD',
            'status'            => $validated['status'] ?? 'in_progress',
            'progress_percent'  => $validated['progress_percent'] ?? 10,
            'start_date'        => $validated['start_date'] ?? now(),
            'delivery_deadline' => $validated['delivery_deadline'] ?? now()->addMonth(),
            'staging_url'       => $validated['staging_url'] ?? null,
            'github_repo_url'   => $validated['github_repo_url'] ?? null,
            'apk_build_url'     => $validated['apk_build_url'] ?? null,
            'figma_url'         => $validated['figma_url'] ?? null,
        ]);

        if (!empty($validated['developer_ids'])) {
            $project->developers()->sync($validated['developer_ids']);
        }

        // Add initial welcome system message
        ProjectMessage::create([
            'client_project_id' => $project->id,
            'user_id'           => $request->user()?->id,
            'sender_type'       => 'system',
            'sender_name'       => 'CodeStudio Engineering Team',
            'message'           => "🎉 Project workspace initialized for '{$project->title}'. Our team has started architectural setup.",
        ]);

        return self::success('Client project created successfully', [
            'project'          => $project->load(['client', 'developers']),
            'client_email'     => $user->email,
            'generated_password'=> $password,
        ], 201);
    }

    /**
     * Update project progress, status, or deliverables.
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $project = ClientProject::findOrFail($id);

        $validated = $request->validate([
            'title'             => ['sometimes', 'string', 'max:190'],
            'description'       => ['nullable', 'string'],
            'status'            => ['sometimes', 'in:discovery,in_progress,review,completed,on_hold'],
            'progress_percent'  => ['sometimes', 'integer', 'min:0', 'max:100'],
            'delivery_deadline' => ['nullable', 'date'],
            'staging_url'       => ['nullable', 'url', 'max:500'],
            'github_repo_url'   => ['nullable', 'url', 'max:500'],
            'apk_build_url'     => ['nullable', 'url', 'max:500'],
            'figma_url'         => ['nullable', 'url', 'max:500'],
            'notes'             => ['nullable', 'string'],
        ]);

        $project->update($validated);

        return self::success('Project updated successfully', $project);
    }

    /**
     * Add a milestone to a project.
     */
    public function addMilestone(Request $request, int $projectId): JsonResponse
    {
        $project = ClientProject::findOrFail($projectId);

        $validated = $request->validate([
            'title'       => ['required', 'string', 'max:190'],
            'description' => ['nullable', 'string'],
            'status'      => ['nullable', 'in:pending,in_progress,completed'],
            'due_date'    => ['nullable', 'date'],
            'sort_order'  => ['nullable', 'integer'],
        ]);

        $milestone = $project->milestones()->create([
            'title'       => $validated['title'],
            'description' => $validated['description'] ?? null,
            'status'      => $validated['status'] ?? 'pending',
            'due_date'    => $validated['due_date'] ?? null,
            'sort_order'  => $validated['sort_order'] ?? ($project->milestones()->count() + 1),
        ]);

        return self::success('Milestone added successfully', $milestone, 201);
    }
}
