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
            // Products as summary objects (when loaded)
            'products' => $this->whenLoaded('products', function () {
                return $this->products->map(function ($product) {
                    return [
                        'id' => $product->id,
                        'title' => $product->title,
                        'slug' => $product->slug,
                        'type' => $product->type,
                        'category' => $product->category,
                        'price' => $product->price,
                        'short_description' => $product->short_description,
                        'thumbnail_url' => $product->thumbnail_url,
                        'tech_stack' => $product->tech_stack,
                        'role_in_project' => $product->pivot?->role_in_project,
                    ];
                });
            }),
            // Counts (when counted)
            'roles_count' => $this->whenCounted('roles'),
            'products_count' => $this->whenCounted('products'),
            'created_at'  => $this->created_at?->toDateTimeString(),
            'updated_at'  => $this->updated_at?->toDateTimeString(),
        ];
    }
}
