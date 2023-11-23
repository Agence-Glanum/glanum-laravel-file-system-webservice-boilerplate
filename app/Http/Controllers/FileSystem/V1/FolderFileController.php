<?php

namespace App\Http\Controllers\FileSystem\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileSystem\V1\FolderFileUpdateRequest;
use Domain\FileSystem\V1\Models\File;
use Domain\FileSystem\V1\Resources\FileCollection;
use Domain\FileSystem\V1\Resources\FileResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Exceptions\InvalidFieldQuery;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class FolderFileController extends Controller
{
    public function index(Request $request, File $folder): FileCollection|JsonResponse
    {
        try {
            $files = QueryBuilder::for(File::class, $request)
                ->allowedFields(['id', 'name', 'type', 'metadata'])
                ->allowedSorts('type')
                ->where('parent_id', $folder->id)
                ->defaultSort('-type')
                ->jsonPaginate(type: 'fastPaginate');

            return (new FileCollection($files))->for($folder);
        } catch (InvalidFieldQuery) {
            return response()->json(status: 400);
        } catch (Throwable $e) {
            report($e);
            return response()->json(status: 500);
        }
    }

    public function show(File $folder, File $file): FileResource
    {
        return new FileResource($file);
    }

    public function update(FolderFileUpdateRequest $request, File $folder, File $file): FileResource
    {
        $file->update($request->safe()->toArray());

        return new FileResource($file);
    }
}
