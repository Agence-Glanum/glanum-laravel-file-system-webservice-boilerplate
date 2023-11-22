<?php

namespace App\Http\Controllers\Auth\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use Domain\Auth\V1\Models\User;
use Domain\Auth\V1\Resources\UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Knuckles\Scribe\Attributes\QueryParam;
use Spatie\QueryBuilder\Exceptions\InvalidFieldQuery;
use Spatie\QueryBuilder\QueryBuilder;
use Throwable;

class UserController extends Controller
{
    #[QueryParam(name: 'page[number]', type: 'int')]
    public function index(Request $request): JsonResponse|AnonymousResourceCollection
    {
        try {
            $users = QueryBuilder::for(User::class, $request)
                ->allowedFields(['id', 'name', 'email'])
                ->allowedFilters(['name', 'email'])
                ->allowedSorts('name')
                ->defaultSort('name')
                ->jsonPaginate(type: 'fastPaginate');

            return UserResource::collection($users);
        } catch (InvalidFieldQuery) {
            return response()->json(status: 400);
        } catch (Throwable $e) {
            report($e);
            return response()->json(status: 500);
        }
    }

    public function store(CreateUserRequest $request): UserResource|JsonResponse
    {
        try {
            $user = DB::transaction(function () use ($request) {
                return User::query()->create([
                    ...$request->safe()->all(),
                    'password' => Hash::make($request->safe()->password)
                ]);
            });

            return new UserResource($user);
        } catch (Throwable $e) {
            report($e);
            return response()->json(status: 500);
        }
    }

    public function show(User $user): UserResource
    {
        return new UserResource($user);
    }

    public function update(UpdateUserRequest $request, User $user): UserResource|JsonResponse
    {
        try {
            DB::transaction(function () use ($request, $user) {
                $user->update([
                    ...$request->safe()->all(),
                ]);
            });

            return new UserResource($user->refresh());
        } catch (Throwable $e) {
            report($e);
            return response()->json(status: 500);
        }
    }

    public function destroy(User $user): JsonResponse
    {
        try {
            DB::transaction(function () use ($user) {
                $user->delete();
            });

            return response()->json();
        } catch (Throwable $e) {
            report($e);
            return response()->json(status: 500);
        }
    }
}
