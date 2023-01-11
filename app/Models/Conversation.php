<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'message_delete_timer',
        'notification',
        'lock_screen',
        'alert_tone',
        'vibrate',
        'file_id',
        'deleted_time',
        'room_sid'
    ];

    public function conversation_users()
    {
        return $this->hasMany('App\Models\ConversationUser', "conversation_id", "id");
    }

    public function message_info()
    {
        return $this->hasMany('App\Models\Message', "conversation_id", "id")
            ->where("deleted_time", ">", now());
    }

    public function file()
    {
        return $this->hasOne('App\Models\files', "id", "file_id");
    }

    public function unread()
    {
        return $this->hasMany('App\Models\ConversationUser', "conversation_id", "id");
    }
}
