<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inbox extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_info_id',
        'title',
        'body',
        'status',
        'file_id',
        'unread'
    ];
}
