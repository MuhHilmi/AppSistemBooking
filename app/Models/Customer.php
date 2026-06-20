<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Customer extends Authenticatable
{
    protected $fillable = [
        'name',
        'phone',
        'password',
        'is_verified'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];
}
