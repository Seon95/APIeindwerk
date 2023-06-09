<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SwapRequest extends Model
{
    protected $fillable = ['sender_id', 'receiver_id', 'item_id', 'my_item_id'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function myItem()
    {
        return $this->belongsTo(Item::class, 'my_item_id');
    }
}
