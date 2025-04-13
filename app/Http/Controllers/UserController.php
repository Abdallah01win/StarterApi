<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCode;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends Controller
{
    public function index(): JsonResponse
    {
        $users = QueryBuilder::for(User::class);

        if (request()->has('list')) {
            $this->authorize('list-users');

            return $users->select('id', 'name')->get();
        }
        $this->authorize('view-users');

        $data = $users->allowedFilters(['name', 'email'])
            ->defaultSort('-created_at')
            ->allowedSorts('created_at')
            ->paginate(_paginatePages());

        return UserResource::collection($data)->response();
    }

    public function store(StoreUserRequest $request): JsonResponse
    {
        $this->authorize('create-users');

        User::create($request->validated());

        return response()->json(true, ResponseCode::CREATED);
    }

    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $this->authorize('update-users');

        $user->update($request->validated());

        return response()->json(true, ResponseCode::ACCEPTED);
    }

    public function destroy(User $user): Response
    {
        $this->authorize('delete-users');

        $user->delete();

        return response()->noContent();
    }
}
