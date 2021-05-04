<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCertification extends Model
{
    use HasFactory;

    protected $table = "user_certifications";

    protected $fillable = [
        'user_id',
        'name',
        'institution',
        'date',
        'link',
        'file',
    ];
}
