<?php

namespace App\Listeners;

use App\Events\CommentCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\CommentNotification;
use Illuminate\Support\Facades\Log;

class SendCommentNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(CommentCreated $event): void
    {
        $comment = $event->comment;
        $post = $comment->post;
        $user = $post->user;


        if ($comment->user_id !== $user->id) {
            $user->notify(new CommentNotification($comment, $post));
        }

    }
}
