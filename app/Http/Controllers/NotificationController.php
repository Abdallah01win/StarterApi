<?php

namespace App\Http\Controllers;

use App\Enums\ResponseCode;
use App\Http\Resources\Notification\NotificationResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\QueryBuilder;

class NotificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        $notifications = QueryBuilder::for($user->unreadNotifications())
            ->defaultSort('-created_at')->get();

        return response()->json(NotificationResource::collection($notifications), ResponseCode::SUCCESS);
    }
}
