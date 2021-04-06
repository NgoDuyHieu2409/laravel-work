<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModifyRequest extends Model
{
    protected $table = 'modify_requests';

    protected $fillable = [
        'worker_id',
        'user_id',
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
        return $this->belongsTo(\App\Models\Work::class, 'work_id', 'id');
    }

    public function worker()
    {
        return $this->belongsTo(\App\Models\Worker::class, 'worker_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(App\Models\User::class, 'user_id', 'id');
    }

}
