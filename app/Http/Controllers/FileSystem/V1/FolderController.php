<?php

namespace App\Http\Controllers\FileSystem\V1;

use App\Http\Controllers\Controller;
use Domain\Auth\V1\Enums\FileType;
use Domain\Auth\V1\Models\User;
use Domain\FileSystem\V1\Models\File;
use Domain\FileSystem\V1\Resources\FileCollection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\Exceptions\InvalidFieldQuery;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class FolderController extends Controller
{
    public function index(Request $request): FileCollection|JsonResponse
    {
        try {
            /* @var File $rootFolder */
            $rootFolder = File::query()
                ->where('owner_id', User::query()->first()->id)
                ->where('type', FileType::Folder)
                ->whereNull('parent_id')
                ->first();

            $files = QueryBuilder::for(File::class, $request)
                ->allowedFields(['id', 'name', 'type', 'metadata', 'created_at', 'updated_at'])
                ->allowedFilters(['type'])
                ->allowedSorts('type', 'name', 'updated_at')
                ->where('parent_id', $rootFolder->id)
                ->defaultSort('-type', 'name')
                ->jsonPaginate(type: 'fastPaginate');

            return (new FileCollection($files))->for($rootFolder);
        } catch (InvalidFieldQuery) {
            return response()->json(status: 400);
        } catch (Throwable $e) {
            report($e);
            return response()->json(status: 500);
        }
    }
}
