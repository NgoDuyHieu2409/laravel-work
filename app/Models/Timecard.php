<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Work;
use App\Models\User;

class Timecard extends Model
{
    use HasFactory;

    protected $table = "timecards";

    protected $fillable = [
        'worker_id',
        'home_id',
        'work_id',
        'checkin_at',
        'checkout_at',
    ];

    public function work()
    {
        return $this->belongsTo(Work::class);
    }
    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id', 'id');
    }
    public function home()
    {
        return $this->belongsTo(User::class, 'home_id', 'id');
    } 
}