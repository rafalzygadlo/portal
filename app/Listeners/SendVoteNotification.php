<?php

namespace App\Listeners;

use App\Events\VoteCreated;
use App\Models\Notification;

class SendVoteNotification
{
    public function handle(VoteCreated $event): void
    {
        // Do not send a notification if voting on your own post
        if ($event->model->user_id === $event->voter->id) {
            return;
        }

        $voteType = $event->value === 1 ? 'vote_up' : 'vote_down';
        $message = $event->value === 1 
            ? "{$event->voter->name} liked your post 👍"
            : "{$event->voter->name} nie liked your post 👎";

        Notification::create([
            'user_id' => $event->model->user_id,
            'from_user_id' => $event->voter->id,
            'type' => $voteType,
            'notifiable_type' => class_basename($event->model),
            'notifiable_id' => $event->model->id,
            'message' => $message,
            'read' => false,
        ]);
    }
}
