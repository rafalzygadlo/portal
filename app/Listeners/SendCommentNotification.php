<?php

namespace App\Listeners;

use App\Events\CommentCreated;
use App\Models\Notification;

class SendCommentNotification
{
    public function handle(CommentCreated $event): void
    {
        // en:Do not send a notification if commenting on your own post
        // de: Senden Sie keine Benachrichtigung, wenn Sie auf Ihren eigenen Beitrag kommentieren
        if ($event->model->user_id === $event->commenter->id) {
            return;
        }
        
        Notification::create([
            'user_id' => $event->model->user_id,
            'from_user_id' => $event->commenter->id,
            'type' => 'comment',
            'notifiable_type' => class_basename($event->model),
            'notifiable_id' => $event->model->id,
            // en: Notification message
            // de: Benachrichtigungsnachricht
            'message' => "{$event->commenter->name} commented on your post",
            'read' => false,
        ]);
    }
}
