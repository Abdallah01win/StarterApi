<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCode;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $this->authorize('view-users');

        $users = User::all();

        return response()->json(UserResource::collection($users), ResponseCode::SUCCESS);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        $this->authorize('create-users');

        User::create($request->validated());

        return response()->json(true, ResponseCode::CREATED);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        $this->authorize('update-users');

        $user->update($request->validated());

        return response()->json(true, ResponseCode::ACCEPTED);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user): JsonResponse
    {
        $this->authorize('delete-users');

        $user->delete();

        return response()->json(true, ResponseCode::NO_CONTENT);
    }
}
