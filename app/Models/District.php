<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\City;

class District extends Model
{
    use HasFactory;

    protected $table = 'districts';
    
    protected $fillable = [
        'name',
        'gso_id',
        'province_id',
    ];

    public function city()
    {
        return $this->belongsTo(City::class);
    }
}
