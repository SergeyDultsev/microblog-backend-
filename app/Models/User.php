<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'user_id',
        'role_id',
        'name',
        'surname',
        'email',
        'password',
        'about',
        'age',
        'birthdate',
        'subscriptions_count',
        'subscriber_count',
        'avatar_url',
        'head_avatar_url',
        'registration_date',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'subscriber_id');
    }

    public function subscribers()
    {
        return $this->hasMany(Subscription::class, 'target_id');
    }

    public function hasRole(string $role)
    {
        return $this->role->name === $role;
    }

    public function getFullNameAttribute()
    {
        return "{$this->name} {$this->surname}";
    }

    public function getIsVerifiedAttribute()
    {
        return !is_null($this->email_verified_at);
    }

    public function getAvatarUrlAttribute()
    {
        return $this->attributes['avatar_url'];
    }

    public function posts()
    {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
