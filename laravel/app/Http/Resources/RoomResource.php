<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class RoomResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'ulid' => $this->ulid,
            'slug' => $this->slug,
            'display_name' => $this->display_name,
            'description' => $this->when(
                $this->resource->wasRecentlyCreated ||
                    \array_key_exists(
                        'description',
                        $this->resource->getAttributes(),
                    ),
                $this->description,
            ),
            'type' => $this->type,
            'created_at' => $this->created_at,
            'archived_at' => $this->archived_at,
        ];
    }
}
