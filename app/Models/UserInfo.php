<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'user_type',
        'status',
        'file_id',
        'device_token'
    ];

    public function user()
    {
        return $this->hasOne('App\Models\User' , "id" , "user_id");
    }

    public function file()
    {
        return $this->hasOne('App\Models\files' , "id" , "file_id");
    }

    public function contacts()
    {
        return $this->hasMany('App\Models\Contact' , "user_info_id" , "user_id");
    }

    public function messages()
    {
        return $this->hasMany('App\Models\Message' , "user_info_id" , "user_id");
    }
}
