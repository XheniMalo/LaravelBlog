<?php
namespace App\Services;

use App\Models\Post;
use App\Models\PostImage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostService
{
    public function getUserPosts()
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        return Post::byUsers($user->id)->with('images')->byLatest()->get();
    }

    public function getPostDetails(Post $post)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();
        return $user->posts()
            ->where('post_id', $post->post_id)
            ->with(['images', 'comments'])
            ->first();
    }

    public function createPost(array $data)
    {
        /**
         * @var User $user
         */
        $user = Auth::user();

        $post = $user->posts()->create([
            'title' => $data['title'],
            'content' => $data['content'],
        ]);

        if (isset($data['images'])) {
            $this->storeImages($post, $data['images']);
        }

        return $post;
    }

    private function storeImages(Post $post, $images)
    {
        foreach ($images as $image) {
            $path = $image->store('posts', 'public');

            $post->images()->create([
                'image_path' => $path,
            ]);
        }
    }

    public function updatePost(Post $post, array $data)
    {
        $post->update([
            'title' => $data['title'],
            'content' => $data['content'],
        ]);

        if (isset($data['images'])) {
            $this->storeImages($post, $data['images']);
        }
    }

    public function deletePost(Post $post)
    {

        foreach ($post->images as $image) {
            Storage::disk('public')->delete($image->image_path);
            $image->delete();
        }
        $post->delete();

    }

    public function deleteImage(PostImage $postImage)
    {
        /**
         * @var Post $post
         */
        $post = $postImage->post;

        if ($post->images()->where('id', $postImage->id)->exists()) {
            Storage::disk('public')->delete($postImage->image_path);
        }

        $postImage->delete();
    }
}

?>