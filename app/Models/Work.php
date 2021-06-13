<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Traits\FullTextSearch;
use App\Models\WorkPhoto;
use App\Models\WorkQualification;
use App\Models\WorkSkill;
use App\Models\WorkRecord;
use App\Models\WorkTag;
use App\Models\WorkApplication;
use App\Models\ModifyRequest;
use App\Models\User;
use App\Models\FavoriteWork;
use App\Models\HomeReview;
use App\Models\company;

class Work extends Model
{
    use HasFactory;
    use SoftDeletes;
    use FullTextSearch;

    protected $table = 'works';
    
    protected $fillable = [
        'user_id',
        'worktime_start_at',
        'worktime_end_at',
        'resttime_start_at',
        'resttime_end_at',
        'resttime_minutes',
        'recruitment_start_at',
        'deadline_type',
        'recruitment_person_count',
        'hourly_wage',
        'transportation_fee',
        'title',
        'work_type',
        'category_id',
        'occupation_id',
        'content',
        'address',
        'access',
        'contact_name',
        'contact_tel',
        'things_to_bring1',
        'things_to_bring2',
        'things_to_bring3',
        'things_to_bring4',
        'things_to_bring5',
        'notes',
        'condition1',
        'condition2',
        'condition3',
        'condition4',
        'condition5',
        'status',
        // 'ovetime_extra_percentages',
        // 'night_extra_percentages',
        // 'share_url',
        // 'working_conditions_pdf_url',
        // 'canceled_at',
        // 'pref',
    ];

    protected $searchable = [
        'title',
        'content',
    ];

    public function work_photos()
    {
        return $this->hasMany(WorkPhoto::class);
    }

    public function work_skills()
    {
        return $this->hasMany(WorkSkill::class);
    }

    public function work_qualifications()
    {
        return $this->hasMany(WorkQualification::class);
    }

    public function work_tags()
    {
        return $this->hasMany(WorkTag::class);
    }

    public function work_records()
    {
        return $this->hasMany(WorkRecord::class);
    }

    public function work_applications()
    {
        return $this->hasMany(WorkApplication::class);
    }

    public function modify_request()
    {
        return $this->hasOne(ModifyRequest::class)->where('worker_id', Auth::id());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function favorites()
    {
        return $this->hasMany(FavoriteWork::class);
    }

    public function home_review()
    {
        return $this->hasOne(HomeReview::class)
                    ->where('worker_id', Auth::id());
    }

    public function company()
    {
        return $this->belongsTo(company::class);
    }

    /**
     * FUNCTIONS
     */
    public function getPdfStringUrl($fieldName)
    {
        if (!$this->{$fieldName}) {
            return null;
        }
        $object = json_decode($this->{$fieldName}, false);
        $ob = $object[0];
        $url = $ob->url;
        $stringUrl = asset(Storage::url($url));
        $fileName = $ob->file_name;
        return [
            'url' => $stringUrl,
            'file_name' => $fileName,
        ];
    }

    public function getWorkTypeText()
    {
        return Config("const.employments.{$this->work_type}");
    }

    public static function getOutstanding()
    {
        return Work::orderBy('hourly_wage', 'desc')
        ->offset(0)
        ->limit(5)
        ->get(); 
    }
}
