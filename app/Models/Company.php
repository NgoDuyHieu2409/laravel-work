<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'companies';
    
    protected $fillable = [
        'email',
        'name',
        'name_english',
        'contact_name',
        'contact_name_english',
        'tel',
        'zipcode',
        'pref',
        'city',
        'logo',
        'description',
        'user_id',
        'address',
        'website_url',
    ];
}
