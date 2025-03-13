<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Models\PostImage;
use App\Models\Post;
use App\Services\PostService;
use Illuminate\Support\Facades\Gate;

class UserPostController extends Controller
{
    protected $postService;

    public function __construct(PostService $postService){
        $this->postService = $postService;
    }
    
    public function index()
    {
        $posts = $this->postService->getUserPosts();

        return view('user.MyPosts', compact('posts'));
    }

    public function create()
    {
        return view('user.createPost');
    }

    public function store( PostRequest $request)
    {
        $post = $this->postService->createPost($request->validated());
    
        return redirect()->route('posts.index', $post->id)->with('success', 'Post updated successfully.');
    }

    public function show(Post $post)
    {
        if (! Gate::allows('view-post', $post)) {
            abort(403, 'Unauthorized action.');
        }
        $post = $this->postService->getPostDetails($post);

        return view('user.PostDetails', compact('post'));
    }

    public function update(Post $post, PostRequest $request)
    {
        $this->postService->updatePost($post, $request->validated());

        return back()->with('success', 'Post updated successfully.');
    }

    public function destroyImage(PostImage $postImage)
    {
        $this->postService->deleteImage($postImage);

        return back()->with('success', 'Image deleted successfully.');
    }

    public function destroy(Post $post)
    {
        $this->postService->deletePost($post);

        return redirect()->route('posts.index')->with('success', 'Post deleted successfully.');
    }


}
