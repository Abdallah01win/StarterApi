<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCode;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\User\InitUserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = User::where('email', $data['email'])->first();

        if (! $user || ! Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => __('auth.failed')], 401);
        }

        $token = $user->createToken($user->name.'-AuthToken', ['*'], now()->addHours(24))->plainTextToken;

        return response()->json(['token' => $token], ResponseCode::SUCCESS);
    }

    public function init_user(): JsonResponse
    {
        $user = Auth::user();

        $user->permissions = $user->getAllPermissions()->pluck('name');

        return response()->json(new InitUserResource($user), ResponseCode::SUCCESS);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(true, ResponseCode::SUCCESS);
    }
}
