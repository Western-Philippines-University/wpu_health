<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalCertificate extends Model
{
    protected $table = 'medical_certificates';

    /**
     * @var list<string>
     */
    protected $guarded = ['id'];
}
