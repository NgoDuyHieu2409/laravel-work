<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Worrk;
use App\Models\User;
use App\Models\WorkRecord;

class WorkApplication extends Model
{
    protected $table = 'work_applications';

    protected $fillable = [
        'work_id',
        'worker_id',
        'status',
        'assigned_at',
        'confirm_yn',
        'confirmed_at',
        'canceled_at',
        'room_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function work()
    {
        return $this->belongsTo(Work::class);
    }

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id', 'id');
    }

    public function work_record()
    {
        return $this->hasOne(WorkRecord::class);
    }

}
