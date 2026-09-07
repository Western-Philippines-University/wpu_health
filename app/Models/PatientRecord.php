<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PatientRecord extends Model
{
    protected $table = 'patient_records';

    /**
     * @var list<string>
     */
    protected $guarded = ['id'];

    const UPDATED_AT = null;
}
