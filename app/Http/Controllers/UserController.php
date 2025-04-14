<?php

namespace App\Http\Controllers;

use App\Enums\UserRole;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class UserController extends BaseController
{
    protected $model = User::class;

    protected $storeRequest = StoreUserRequest::class;

    protected $updateRequest = UpdateUserRequest::class;

    public function index(Request $request): JsonResponse
    {
        $users = QueryBuilder::for(User::class);

        if (_hasList($request)) {
            $this->authorize('list-users');

            return response()->json($users->select('id', 'name')->get());
        }
        $this->authorize('view-users');

        $users = $users->where('role', '>', UserRole::SUPERADMIN)
            ->whereNot('id', Auth::id())
            ->allowedFilters(['name', AllowedFilter::exact('role')])
            ->defaultSort('-created_at')
            ->allowedSorts('created_at')
            ->paginate(_paginatePages());

        return UserResource::collection($users)->response();
    }
}
