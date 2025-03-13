<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Models\PostImage;
use App\Services\PostService;

class AdminPostsController extends Controller
{
    protected $postService;

    public function __construct(PostService $postservice)
    {
        $this->postService = $postservice;
    }

    public function index()
    {
        $posts = Post::with('user','images')->get();
        return view('admin.posts', compact('posts'));
    }

    public function edit(Post $post)
    {
        return view('admin.editPost', compact('post'));

    }

    public function update(Post $post, PostRequest $request)
    {
        $this->postService->updatePost($post, $request->validated());

        return redirect()->route('post.index')->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {

        $this->postService->deletePost($post);

        return response()->json(['message' => 'Post deleted successfully!']);
    }

    public function destroyImage(PostImage $postImage)
    {
        $this->postService->deleteImage($postImage);

        return back()->with('success', 'Image deleted successfully.');
    }
}
