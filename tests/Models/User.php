<?php

namespace Statikbe\FilamentFlexibleContentBlocks\Tests\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}
