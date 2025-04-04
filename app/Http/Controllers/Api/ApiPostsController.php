<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\PostImage;

class ApiPostsController extends Controller
{
    public function storePost(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'images.*' => 'image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $post = Post::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        if ($request->hasFile('images')) {
            $uploadedImages = [];
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('posts', 'public');
                    $postImage = PostImage::create([
                        'post_id' => $post->id,
                        'image_path' => $path,
                    ]);
                    $uploadedImages[] = $postImage;
                }
            }

            if (count($uploadedImages) > 0) {
                return response()->json([
                    'message' => 'Post created successfully with ' . count($uploadedImages) . ' images',
                    'post' => $post->load('images'),
                ], 201);
            } else {
                return response()->json([
                    'message' => 'Post created but images failed to upload',
                    'post' => $post,
                ], 201);
            }
        }

        return response()->json([
            'message' => 'Post created successfully without images',
            'post' => $post,
        ], 201);
    }
}
