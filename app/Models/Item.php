<?php

namespace App\Models;

use App\Models\User;


use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = ['name', 'category_id', 'quantity', 'description', 'user_id', 'images'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
