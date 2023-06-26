<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Item extends Model
{
    protected $fillable = ['name', 'category_id', 'quantity', 'description', 'user_id', 'images'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'email', 'password'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function items()
    {
        return $this->hasMany(Item::class);
    }


    public function sentSwapRequests()
    {
        return $this->hasMany(SwapRequest::class, 'sender_id');
    }

    public function receivedSwapRequests()
    {
        return $this->hasMany(SwapRequest::class, 'receiver_id');
    }
}
