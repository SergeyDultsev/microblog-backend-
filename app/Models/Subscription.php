<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'subscriber_id',
        'target_id',
        'created_at'
    ];

    protected $primaryKey = 'subscription_id';
    public $incrementing = true;

    public function subscriber()
    {
        return $this->belongsTo(User::class, 'subscriber_id');
    }

    public function target()
    {
        return $this->belongsTo(User::class, 'target_id');
    }
}
