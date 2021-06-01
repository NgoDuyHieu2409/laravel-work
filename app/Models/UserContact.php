<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\City;
use App\Models\District;

class UserContact extends Model
{
    use HasFactory;

    protected $table = "user_contacts";

    protected $fillable = [
        'user_id',
        'phone',
        'birthday',
        'job_title',
        'sex',
        'city',
        'district',
        'address',
        'summary',
    ];

    public function rs_city()
    {
        return $this->belongsTo(City::class, 'city', 'id');
    }

    public function rs_district()
    {
        return $this->belongsTo(District::class, 'district', 'id');
    }
}
