<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'body' => $this->deleted_at ? null : $this->body,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'sender' => new SenderResource($this->whenLoaded('sender')),
            'deleted_at' => $this->deleted_at,
        ];
    }
}
