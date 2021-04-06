<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    public function work()
    {
        return $this->belongsTo('App\Work');
    }

    public function worker()
    {
        return $this->belongsTo('App\Worker');
    }

    public function work_record()
    {
        return $this->hasOne(WorkRecord::class);
    }

}
