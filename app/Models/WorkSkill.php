<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkSkill extends Model
{
    protected $table = 'work_skill';

    protected $fillable = [
        'work_id',
        'skill_id',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */
    public function skill()
    {
        return $this->belongsTo(Skill::class);
    }
}
