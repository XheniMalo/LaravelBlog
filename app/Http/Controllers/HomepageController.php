<?php

namespace App\Http\Controllers;

use App\Models\Post;

class HomepageController extends Controller
{
    public function showHomepage()
    {
        $posts = Post::with(['images', 'user', 'comments.user', 'comments.replies.user'])->latest()->get();
        return view('user.home', compact('posts'));
    }

    
    public function show($id)
    {
        $post = Post::with('images', 'user', 'comments.user', 'comments.replies')->findOrFail($id);
        return view('user.PostDetailsHomepage', compact('post'));
    }
}
