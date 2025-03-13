<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;
    protected $primaryKey = 'id';
    protected $table = 'posts_comments';

    protected $fillable = [
        'user_id',
        'post_id',
        'parent_id',
        'body'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id','id');
    }

    public function post(){
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }

    public function replies(){
        return $this->hasMany(Comment::class, 'parent_id');
    }
}
