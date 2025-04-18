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

class UserController extends BaseController
{
    protected string $model = User::class;

    protected ?string $storeRequest = StoreUserRequest::class;

    protected ?string $updateRequest = UpdateUserRequest::class;

    protected string $modelResource = UserResource::class;

    public function index(Request $request): JsonResponse
    {
        $paginationQuery = function ($query) {
            return $query->whereNot('role', UserRole::SUPERADMIN)->whereNot('id', Auth::id());
        };

        return parent::readAll(
            $request,
            $paginationQuery,
            null,
            ['id', 'name'],
            ['name', AllowedFilter::exact('role')],
            true
        );
    }
}
