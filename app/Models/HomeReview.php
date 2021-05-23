<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Work;

class HomeReview extends Model
{
    use HasFactory;

    protected $table = 'home_reviews';

    protected $fillable = [
        'worker_id',
        'user_id',
        'comment',
        'good_yn1',
        'good_yn2',
        'good_yn3',
        'work_id',
    ];

    public function worker()
    {
        return $this->belongsTo(User::class);
    }

    public function work()
    {
        return $this->belongsTo(Work::class);
    }
}
