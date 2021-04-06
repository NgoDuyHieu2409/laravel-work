<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkQualification extends Model
{
    protected $table = 'work_qualification';

    protected $fillable = [
        'work_id',
        'qualification_id',
        'required_yn',
    ];
}
