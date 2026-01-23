<?php

namespace App\Listeners;

use App\Events\CommentCreated;
use App\Models\Notification;

class SendCommentNotification
{
    public function handle(CommentCreated $event): void
    {
        // Nie wysyłaj powiadomienia jeśli komentuje się własny post
        if ($event->model->user_id === $event->commenter->id) {
            return;
        }

        Notification::create([
            'user_id' => $event->model->user_id,
            'from_user_id' => $event->commenter->id,
            'type' => 'comment',
            'notifiable_type' => class_basename($event->model),
            'notifiable_id' => $event->model->id,
            'message' => "{$event->commenter->name} skomentował Twój post",
            'read' => false,
        ]);
    }
}
