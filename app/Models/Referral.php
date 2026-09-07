<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    protected $table = 'referrals';

    /**
     * @var list<string>
     */
    protected $guarded = ['id'];
}
