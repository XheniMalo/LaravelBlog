<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
        return $this->hasMany(Comment::class, 'post_id', 'post_id')->latest();
    }

    public function topLevelComments()
    {
        return $this->comments()->whereNull('parent_id');
    }

}
