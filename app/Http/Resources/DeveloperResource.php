<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeveloperResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'name'             => $this->name,
            'slug'             => $this->slug,
            'email'            => $this->email,
            'phone'            => $this->phone,
            'bio'              => $this->bio,
            'avatar'           => $this->avatar,
            'github_url'       => $this->github_url,
            'linkedin_url'     => $this->linkedin_url,
            'portfolio_url'    => $this->portfolio_url,
            'twitter_url'      => $this->twitter_url,
            'skills'           => $this->skills ?? [],
            'experience_years' => $this->experience_years,
            'status'           => $this->status,
            'sort_order'       => $this->sort_order,
            // Roles as full objects (when loaded)
            'roles' => DeveloperRoleResource::collection(
                $this->whenLoaded('roles')
            ),
            // Counts (when counted)
            'roles_count' => $this->whenCounted('roles'),
            'created_at'  => $this->created_at?->toDateTimeString(),
            'updated_at'  => $this->updated_at?->toDateTimeString(),
        ];
    }
}
