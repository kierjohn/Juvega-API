<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountVerification extends Model
{
    public $table = "account_verification";
    use HasFactory;

    protected $fillable = [
        'user_id',
        'code',
        'type',
        'is_confirm',  
    ];
}
