<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = ['sender_id','contact','message'];

    protected $dates = ['send_time'];

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
