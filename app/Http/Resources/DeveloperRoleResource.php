<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeveloperRoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'          => $this->id,
            'name'        => $this->name,
            'slug'        => $this->slug,
            'color'       => $this->color,
            'icon'        => $this->icon,
            'description' => $this->description,
            'sort_order'  => $this->sort_order,
            // developer count only when loaded
            'developers_count' => $this->whenCounted('developers'),
            'created_at'  => $this->created_at?->toDateTimeString(),
        ];
    }
}
