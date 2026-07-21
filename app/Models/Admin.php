<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Admin extends Authenticatable
{
    protected $table = 'admins';

    /** @var list<string> */
    protected $fillable = [
        'username',
        'password',
    ];

    /** @var list<string> */
    protected $hidden = [
        'password',
    ];

    const UPDATED_AT = null;

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }
}
