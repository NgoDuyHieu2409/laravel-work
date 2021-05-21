<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkerSkill extends Model
{
    use HasFactory;

    protected $table = 'worker_skill';

    protected $fillable = [
        'worker_id',
        'skill_id',
        'user_id',
        'work_id',
    ];

    // public function skill()
    // {
    //     return $this->belongsTo(\App\Models\Skill::class);
    // }
}
