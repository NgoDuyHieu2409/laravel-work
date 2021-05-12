<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Timecard;
use App\Models\Work;
use App\Models\User;

class WorkRecord extends Model
{
    protected $table = 'work_records';

    protected $fillable = [
        'invoice_id',
        'worker_id',
        'user_id',
        'work_id',
        'work_application_id',
        'title',
        'work_date',
        'scheduled_worktime_start_at',
        'scheduled_worktime_end_at',
        'worktime_start_at',
        'worktime_end_at',
        'scheduled_resttime_start_at',
        'scheduled_resttime_end_at',
        'resttime_start_at',
        'resttime_end_at',
        'resttime_minutes',
        'hourly_wage',
        'base_worktime',
        'overtime_worktime',
        'nighttime_worktime',
        'worker_first_name',
        'worker_last_name',
        'worker_first_name_kana',
        'worker_last_name_kana',
        'worker_sex',
        'base_wage',
        'ovetime_percentages',
        'nighttime_percentages',
        'ovetime_wage',
        'nighttime_wage',
        'transportation_fee',
        'total_wage',
        'transfer_request_status',
        'transfer_requested_at',
        'transfered_at',
        'commission_fee',
        'commission_fee_tax',
        'commission_fee_tax_rate',
        'fixed_yn',
        'fixed_at',
        'company_id',
    ];

    public function work()
    {
        return $this->belongsTo(Work::class, 'work_id', 'id');
    }

    public function home()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }    

    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id', 'id');
    }

    public function checkin()
    {
        $checkin = '00:00';
        $timecard =  Timecard::where('work_id', $this->work_id)->where('worker_id', $this->worker_id)->first();
        if($timecard) {
            $checkin =  $timecard->checkin_at;
        }
        return $checkin;
    }

    public function checkout()
    {
        $checkout = '00:00';
        $timecard =  Timecard::where('work_id', $this->work_id)->where('worker_id', $this->worker_id)->first();
        if($timecard) {
            $checkout =  $timecard->checkout_at;
        }
        return $checkout;
    }

    public function total_worktime() 
    {
	    return $this->base_worktime + $this->overtime_worktime + $this->nighttime_worktime;
    }
}