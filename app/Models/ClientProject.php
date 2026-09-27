<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ClientProject extends Model
{
    protected $fillable = [
        'user_id',
        'project_code',
        'title',
        'slug',
        'description',
        'status',
        'progress_percent',
        'platform',
        'budget',
        'currency',
        'start_date',
        'delivery_deadline',
        'staging_url',
        'github_repo_url',
        'apk_build_url',
        'figma_url',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'progress_percent' => 'integer',
            'budget' => 'decimal:2',
            'start_date' => 'date',
            'delivery_deadline' => 'date',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (ClientProject $project) {
            if (empty($project->slug)) {
                $project->slug = Str::slug($project->title) . '-' . strtolower(Str::random(5));
            }
            if (empty($project->project_code)) {
                $project->project_code = 'PRJ-' . date('Y') . '-' . strtoupper(Str::random(4));
            }
        });
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(ProjectMilestone::class, 'client_project_id')->orderBy('sort_order');
    }

    public function developers(): BelongsToMany
    {
        return $this->belongsToMany(
            Developer::class,
            'client_project_developer',
            'client_project_id',
            'developer_id'
        )->withPivot('role_in_project')->withTimestamps();
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ProjectMessage::class, 'client_project_id')->latest();
    }
}
