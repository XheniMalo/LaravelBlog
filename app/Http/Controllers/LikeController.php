<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggleLike(Post $post)
    {
        $user = Auth::user();

        $isLiked = $post->likes()->where('user_id', $user->id)->exists();

        if ($isLiked) {
            $post->likes()->detach($user->id);
        } else {
            $post->likes()->attach($user->id);
        }

        return redirect()->back();
    }


    public function getLikes(Post $post)
    {
        return response()->json([
            'likes' => $post->likes()->with('user')->get()
        ]);
    }

    public function getComments(Post $post)
    {
        return response()->json([
            'comments' => $post->comments()->with('user')->get()
        ]);
    }


}

