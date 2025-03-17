<?php

namespace App\Notifications;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class CommentNotification extends Notification
{
    use Queueable;

    protected $comment;
    protected $post;

    public function __construct(Comment $comment, Post $post)
    {
        $this->comment = $comment;
        $this->post = $post;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
        ->subject('New Comment on Your Post: ' . $this->post->title)
        ->greeting('Hello ' . $notifiable->name . '!')
        ->line($this->comment->user->name . ' commented on your post.')
        ->line('Comment: "' . substr($this->comment->body, 0, 100) . (strlen($this->comment->body) > 100 ? '...' : '') . '"');
    }
}
