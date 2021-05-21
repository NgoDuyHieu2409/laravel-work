<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerReview extends Model
{
    use HasFactory;

    protected $table = 'worker_reviews';

    protected $fillable = [
        'worker_id',
        'user_id',
        'work_id',
        'liked',
        'comment',
    ];
}
