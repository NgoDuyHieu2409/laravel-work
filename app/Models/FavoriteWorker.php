<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class FavoriteWorker extends Model
{
    use HasFactory;

    protected $table = 'favorite_workers';

    protected $fillable = [
        'worker_id',
        'home_id',
    ];

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id', 'id');
    }
}
