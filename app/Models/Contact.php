<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_info_id',
        'contact_id',
        'status',
        'favorite',
    ];

    public function contact_info()
    {
        return $this->hasOne('App\Models\UserInfo' , "id" , "contact_id");
    }

    public function user_info()
    {
        return $this->hasOne('App\Models\UserInfo' , "id" , "user_info_id");
    }
}
