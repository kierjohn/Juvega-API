<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversationUser extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'user_info_id',
        'unread_message',
    ];

    public function conversation()
    {
        return $this->hasOne('App\Models\Conversation', "id", "conversation_id");
    }

    public function user_info()
    {
        return $this->hasOne('App\Models\UserInfo', "id", "user_info_id");
    }

    public function user_status()
    {
        return $this->hasOne('App\Models\UserActivity', "user_info_id", "user_info_id");
    }

    public function user_setting()
    {
        return $this->hasOne('App\Models\UserActivity', "user_info_id", "user_info_id");
    }
}
