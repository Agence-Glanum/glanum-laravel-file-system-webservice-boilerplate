<?php

namespace Domain\Auth\V1\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->whenHas('id', $this->id),
            'name' => $this->whenHas('name', $this->name),
            'email' => $this->whenHas('email', $this->email),
            'created_at' => $this->whenHas('created_at', $this->created_at)
        ];
    }
}
