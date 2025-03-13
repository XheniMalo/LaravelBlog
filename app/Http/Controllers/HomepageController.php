<?php

namespace App\Http\Controllers;

use App\Models\Post;

class HomepageController extends Controller
{
    public function showHomepage()
    {
        $posts = Post::with('images', 'user', 'comments')->latest()->get();
        return view('user.home', compact('posts'));
    }

    public function show($id)
    {
        $post = Post::with('images', 'user', 'comments.user')->findOrFail($id);
        return view('user.PostDetailsHomepage', compact('post'));
    }
}
