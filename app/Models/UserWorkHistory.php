<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWorkHistory extends Model
{
    use HasFactory;

    protected $table = "user_work_histories";

    protected $fillable = [
        'user_id',
        'position',
        'company',
        'from_date',
        'to_date',
        'current_job',
        'description',
    ];
}
