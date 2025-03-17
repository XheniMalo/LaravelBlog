<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';
    protected $primaryKey = 'post_id';
    protected $keyType = 'int';
    public $incrementing = true;
    protected $fillable = [
        'user_id',
        'title',
        'content',
    ];

    public function images()
    {
        return $this->hasMany(PostImage::class, 'post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopeByUsers($query, $user_id)
    {
        return $query->where('user_id', $user_id);
    }

    public function scopeByLatest($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopeWithImages($query)
    {
        return $query->whereHas('images');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'post_id')->whereNull('parent_id')->latest();
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'posts_likes', 'post_id', 'user_id');
    }

    public function isLikedByUser($user = null)
    {
     
        $userId = $user ? $user->id : Auth::id();

        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function likesCount()
    {
        return $this->likes()->count();
    }
}