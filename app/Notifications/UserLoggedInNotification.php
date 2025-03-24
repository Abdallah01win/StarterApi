<?php

namespace App\Notifications;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class UserLoggedInNotification extends Notification implements ShouldBroadcast, ShouldQueue
{
    use  Queueable;

    public function __construct(public $notification, public $user) {}

    public function via()
    {
        return ['broadcast'];
    }

    public function toArray($notifiable)
    {
        return ['notification' => $this->notification];
    }

    public function broadcastOn(): array
    {
        // $user_id = $this->notification['pivot']['userId'];

        return [
            new PrivateChannel('Notification.' . $this->user->id),
        ];
    }

    public function broadcastAs()
    {
        return 'GeneralEvent';
    }
}
