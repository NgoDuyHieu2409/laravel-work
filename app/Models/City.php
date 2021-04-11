<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\District;

class City extends Model
{
    use HasFactory;

    protected $table = 'provinces';
    
    protected $fillable = [
        'name',
        'gso_id',
    ];

    public function districts()
    {
        return $this->hasMany(District::class);
    }
}
