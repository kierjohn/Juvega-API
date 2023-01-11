<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'url',
        'extension',
        'bucket',
    ];

    public function info()
    {
        return $this->hasOne('App\Models\UserInfo' , "file_id" , "id");
    }

    public function Conversation()
    {
        return $this->hasOne('App\Models\Conversation' , "file_id" , "id");
    }
}
