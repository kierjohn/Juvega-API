<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_info_id',
        'status',
        'notification',
        'delete_timer',
        'lock_screen',
        'alert_tone',
        'vibrate',
        'timezone'
    ];
}
