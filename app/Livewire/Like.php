<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

class Like extends Component
{
    public $post;
    public $isLiked;
    public $likesCount;

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->isLiked = $post->isLikedByUser();
        $this->likesCount = $post->likes()->count();
    }

    public function toggleLike()
    {
        $user = Auth::user();

        if ($this->isLiked) {
            $this->post->likes()->detach($user->id);
            $this->likesCount--;
        } else {
            $this->post->likes()->attach($user->id);
            $this->likesCount++;
        }

        $this->isLiked = !$this->isLiked;
    }

    public function render()
    {
        return view('livewire.like');
    }
}
