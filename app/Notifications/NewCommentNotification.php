<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification
{
    use Queueable;

    protected $comment;

    /**
     * Create a new notification instance.
     */
    public function __construct(Comment $comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // We'll send notifications via database (for in-app display) and email.
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('New Comment on your Article')
                    ->greeting('Hello ' . $notifiable->first_name . '!')
                    ->line('A new comment has been posted on the article: "' . $this->comment->article->title . '".')
                    ->action('View Comment', url('/articles/' . $this->comment->article->id . '#comment-' . $this->comment->id))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'comment_id' => $this->comment->id,
            'comment_body' => $this->comment->body,
            'article_id' => $this->comment->article->id,
            'article_title' => $this->comment->article->title,
            'comment_author_name' => $this->comment->user->first_name,
            'url' => url('/articles/' . $this->comment->article->id . '#comment-' . $this->comment->id),
        ];
    }
}
