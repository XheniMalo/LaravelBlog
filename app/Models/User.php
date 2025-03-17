<?php

namespace App\Models;

use App\Enums\Gender;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Authenticatable implements CanResetPasswordContract
{

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */



    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
        'profession',
        'birthday',
        'gender'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'gender' => Gender::class,
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function profilePicture()
    {
        return $this->hasOne(ProfilePicture::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    public function likes()
    {
        return $this->belongsToMany(Post::class, 'posts_likes', 'user_id', 'post_id');
    }

    public function hasRole($roleName)
    {
        return $this->role && $this->role->name === $roleName;
    }

    public function scopeByRole($query, $roleName)
    {
        return $query->whereHas('role', function ($query) use ($roleName) {
            $query->where('name', $roleName);
        });
    }

}
