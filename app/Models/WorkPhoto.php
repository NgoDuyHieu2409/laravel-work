<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkPhoto extends Model
{
    protected $table = 'work_photos';

    protected $fillable = [
        'work_id',
        'url',
        'title',
    ];

}
