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
            'id' => $this->whenHas('id', $this->id),
            'name' => $this->whenHas('name', $this->name),
            'type' => $this->whenHas('type', $this->type),
            'sync' => true,
            'root' => $this->parent_id === null,
            'metadata' => $this->whenHas('metadata', [
                ...$this->metadata,
                'parentDirId' => $this->parent_id
            ]),
            'created_at' => $this->whenHas('created_at', $this->created_at),
            'updated_at' => $this->whenHas('updated_at', $this->updated_at)
        ];
    }
}

