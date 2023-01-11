<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_info_id',
        'message',
        'conversation_id',
        'created_by',
        'deleted_time'
    ];

    public function conversation()
    {
        return $this->hasOne('App\Models\Conversation' , "id" , "conversation_id");
    }

    public function user_info()
    {
        return $this->hasOne('App\Models\UserInfo' , "id" , "user_info_id");
    }

}
