<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkTag extends Model
{
    protected $table = 'work_tag';

    protected $fillable = [
        'work_id',
        'tag_id',
    ];

    public function tag()
    {
        return $this->belongsTo('App\Tag');
    }

}
