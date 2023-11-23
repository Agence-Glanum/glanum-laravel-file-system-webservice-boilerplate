<?php

namespace Domain\FileSystem\V1\Resources;

use Domain\FileSystem\V1\Models\File;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class FileCollection extends ResourceCollection
{
    public File $file;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        $baseFolder = (new FileResource($this->file))->toArray($request);

        return [
            'data' => [
                ...$baseFolder,
                'files' => $this->collection
            ],
        ];
    }

    public function for(File $file): self
    {
        $this->file = $file;

        return $this;
    }
}
