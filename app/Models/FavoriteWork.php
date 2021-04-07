<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteWork extends Model
{
    protected $table = 'favorite_works';

    protected $fillable = [
        'worker_id',
        'work_id',
    ];

    public function work()
    {
        return $this->belongsTo(App\Models\Work::class);
    }
}
