<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Work;
use App\Models\User;

class ModifyRequest extends Model
{
    protected $table = 'modify_requests';

    protected $fillable = [
        'worker_id',
        'home_id',
        'work_id',
        'comment',
        'scheduled_worktime_start_at',
        'scheduled_worktime_end_at',
        'modify_worktime_start_at',
        'modify_worktime_end_at',
        'resttime_minutes',
        'ovetime_percentages',
        'nighttime_percentages',
        'ovetime_wage',
        'nighttime_wage',
        'base_wage',
        'transportation_fee',
        'approval_status',
        'approved_at',
    ];

    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id', 'id');
    }

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'home_id', 'id');
    }

}
