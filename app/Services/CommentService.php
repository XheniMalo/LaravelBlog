<?php

namespace App\Services;

use App\Models\Comment;
use App\Models\User;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Notifications\CommentNotification;
use App\Events\CommentCreated;
use Illuminate\Support\Facades\Log;

class CommentService
{
    public function createComment(array $data)
    {
        /**
         * @var User $user
         */

         $user=Auth::user();
         $comment = $user->comments()->create([
            'post_id' => $data['post_id'],
            'body' => $data['body'],
            'parent_id' => $data['parent_id'] ?? null,
         ]);

         event(new CommentCreated($comment));

         return $comment;
    }
    
    public function createReply(array $data)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        $reply= $user->comments()->create([
            'post_id' =>$data['post_id'],
            'parent_id' =>$data['parent_id'],
            'body' => $data['body']
        ]);

        return $reply;
    }
    
    public function updateComment(Comment $comment, array $data)
    {
        $comment->update([
            'body' => $data['body']
        ]);
    }
    
}