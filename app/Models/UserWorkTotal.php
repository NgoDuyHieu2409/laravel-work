<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWorkTotal extends Model
{
    use HasFactory;
    protected $table = "user_work_totals";

    protected $fillable = [
        'user_id',
        'total_worktime',
        'total_workcount',
        'penalty_point',
    ];
}
