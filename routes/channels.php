<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('Notification.{id}', function ($user, $id) {
    return true;
}, ['guards' => ['sanctum']]);
