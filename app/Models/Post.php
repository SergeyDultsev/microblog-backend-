<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'post_id',
        'user_id',
        'text_content',
        'media_content',
        'count_like',
        'count_comment',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
    public $incrementing = false;

    protected $primaryKey = 'post_id';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class, 'post_id', 'post_id');
    }

    public function getHasLikedAttribute()
    {
        $user = Auth::id();
        if (!$user) {
            return false;
        }
        return $this->likes()->where('user_id', $user)->exists();
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'post_id');
    }
}
