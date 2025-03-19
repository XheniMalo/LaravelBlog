<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;

class AdminCommentsController extends Controller
{


    public function destroyComment(Comment $comment)
    {
        if ($comment->replies) {
            foreach ($comment->replies as $reply) {
                $reply->delete();
            }
        }
        
        $comment->delete();
        
        return response()->json(['message' => 'Comment deleted successfully!']);
    }
    
    public function destroyReply(Comment $comment)
    {
        if ($comment->replies) {
            foreach ($comment->replies as $reply) {
                $reply->delete();
            }
        }
        
        $comment->delete();
        
        return response()->json(['message' => 'Reply deleted successfully!']);
    }
}
