<?php

namespace App\Http\Controllers\Api\Client;

use App\Events\NewProjectMessageEvent;
use App\Http\Controllers\Controller;
use App\Models\ClientProject;
use App\Models\ProjectMessage;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ClientPortalController extends Controller
{
    use ApiResponse;

    /**
     * Get authenticated client profile and overview stats.
     */
    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        $projectsCount = ClientProject::where('user_id', $user->id)->count();
        $activeCount = ClientProject::where('user_id', $user->id)->whereIn('status', ['discovery', 'in_progress', 'review'])->count();
        $completedCount = ClientProject::where('user_id', $user->id)->where('status', 'completed')->count();

        return self::success('Client Profile', [
            'user' => [
                'id'    => $user->id,
                'name'  => $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
            'stats' => [
                'total_projects'     => $projectsCount,
                'active_projects'    => $activeCount,
                'completed_projects' => $completedCount,
            ],
        ]);
    }

    /**
     * List all projects assigned to this client.
     */
    public function projects(Request $request): JsonResponse
    {
        $user = $request->user();

        $projects = ClientProject::where('user_id', $user->id)
            ->with(['milestones', 'developers.roles'])
            ->latest('updated_at')
            ->get();

        return self::success('Client Projects', $projects->map(fn(ClientProject $p) => [
            'id'                => $p->id,
            'project_code'      => $p->project_code,
            'title'             => $p->title,
            'slug'              => $p->slug,
            'description'       => $p->description,
            'status'            => $p->status,
            'progress_percent'  => $p->progress_percent,
            'platform'          => $p->platform,
            'budget'            => $p->budget,
            'currency'          => $p->currency,
            'start_date'        => $p->start_date?->format('Y-m-d'),
            'delivery_deadline' => $p->delivery_deadline?->format('Y-m-d'),
            'staging_url'       => $p->staging_url,
            'apk_build_url'     => $p->apk_build_url,
            'milestones_count'  => $p->milestones->count(),
            'completed_milestones' => $p->milestones->where('status', 'completed')->count(),
            'developers'        => $p->developers->map(fn($d) => [
                'id'               => $d->id,
                'name'             => $d->name,
                'slug'             => $d->slug,
                'avatar'           => $d->avatar,
                'role_in_project'  => $d->pivot?->role_in_project ?? 'Assigned Engineer',
                'primary_role'     => $d->roles->first()?->name ?? 'Software Engineer',
                'primary_color'    => $d->roles->first()?->color ?? '#2563eb',
            ]),
        ]));
    }

    /**
     * Get single project details by code or slug.
     */
    public function projectDetails(Request $request, string $identifier): JsonResponse
    {
        $user = $request->user();

        $project = ClientProject::where('user_id', $user->id)
            ->where(function ($q) use ($identifier) {
                if (is_numeric($identifier)) {
                    $q->where('id', (int) $identifier);
                } else {
                    $q->where('slug', $identifier)->orWhere('project_code', $identifier);
                }
            })
            ->with([
                'milestones' => fn($q) => $q->orderBy('sort_order'),
                'developers.roles',
                'messages' => fn($q) => $q->latest()->limit(50),
            ])
            ->firstOrFail();

        return self::success('Project Details', [
            'id'                => $project->id,
            'project_code'      => $project->project_code,
            'title'             => $project->title,
            'slug'              => $project->slug,
            'description'       => $project->description,
            'status'            => $project->status,
            'progress_percent'  => $project->progress_percent,
            'platform'          => $project->platform,
            'budget'            => $project->budget,
            'currency'          => $project->currency,
            'start_date'        => $project->start_date?->format('M d, Y'),
            'delivery_deadline' => $project->delivery_deadline?->format('M d, Y'),
            'staging_url'       => $project->staging_url,
            'github_repo_url'   => $project->github_repo_url,
            'apk_build_url'     => $project->apk_build_url,
            'figma_url'         => $project->figma_url,
            'notes'             => $project->notes,
            'milestones'        => $project->milestones->map(fn($m) => [
                'id'           => $m->id,
                'title'        => $m->title,
                'description'  => $m->description,
                'status'       => $m->status,
                'due_date'     => $m->due_date?->format('M d, Y'),
                'completed_at' => $m->completed_at?->format('M d, Y'),
                'sort_order'   => $m->sort_order,
            ]),
            'developers'        => $project->developers->map(fn($d) => [
                'id'               => $d->id,
                'name'             => $d->name,
                'slug'             => $d->slug,
                'avatar'           => $d->avatar,
                'experience_years' => $d->experience_years,
                'role_in_project'  => $d->pivot?->role_in_project ?? 'Assigned Engineer',
                'primary_role'     => $d->roles->first()?->name ?? 'Software Engineer',
                'primary_color'    => $d->roles->first()?->color ?? '#2563eb',
            ]),
            'messages'          => $project->messages->map(fn($msg) => [
                'id'              => $msg->id,
                'sender_type'     => $msg->sender_type,
                'sender_name'     => $msg->sender_name,
                'sender_avatar'   => $msg->sender_avatar,
                'message'         => $msg->message,
                'attachment_url'  => $msg->attachment_url,
                'attachment_name' => $msg->attachment_name,
                'created_at'      => $msg->created_at?->toIso8601String(),
                'time'            => $msg->created_at?->diffForHumans(),
            ]),
        ]);
    }

    /**
     * Post a new message or update on the project board.
     */
    public function sendMessage(Request $request, int $projectId): JsonResponse
    {
        $user = $request->user();

        $project = ClientProject::where('user_id', $user->id)->findOrFail($projectId);

        $validated = $request->validate([
            'message'         => ['required', 'string', 'max:3000'],
            'attachment_url'  => ['nullable', 'url', 'max:500'],
            'attachment_name' => ['nullable', 'string', 'max:190'],
        ]);

        $message = ProjectMessage::create([
            'client_project_id' => $project->id,
            'user_id'           => $user->id,
            'sender_type'       => $user->role === 'admin' ? 'admin' : 'client',
            'sender_name'       => $user->name,
            'sender_avatar'     => null,
            'message'           => $validated['message'],
            'attachment_url'    => $validated['attachment_url'] ?? null,
            'attachment_name'   => $validated['attachment_name'] ?? null,
        ]);

        // Broadcast real-time live message event via Laravel Reverb!
        try {
            broadcast(new NewProjectMessageEvent($message))->toOthers();
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('Broadcast failed: ' . $e->getMessage());
        }

        return self::success('Message sent successfully', [
            'id'              => $message->id,
            'sender_type'     => $message->sender_type,
            'sender_name'     => $message->sender_name,
            'message'         => $message->message,
            'attachment_url'  => $message->attachment_url,
            'attachment_name' => $message->attachment_name,
            'created_at'      => $message->created_at?->toIso8601String(),
            'time'            => 'Just now',
        ]);
    }
}
