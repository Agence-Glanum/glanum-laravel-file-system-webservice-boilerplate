<?php

namespace Domain\FileSystem\V1\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'sync' => true,
            'root' => $this->parent_id === null,
            'metadata' => [
                ...$this->metadata,
                'parentDirId' => $this->parent_id
            ],
        ];
    }
}

