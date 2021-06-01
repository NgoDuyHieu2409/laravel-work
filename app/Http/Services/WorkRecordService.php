<?php

namespace App\Http\Services;

use App\Helpers\WageCalculatorHelper;
use App\Models\Work;
use App\Models\WorkApplication;
use App\Models\User as Worker;
use App\Models\WorkRecord;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class WorkRecordService
{
    protected $workService;

    public function __construct(WorkService $workService)
    {
        $this->workService = $workService;
    }

    public function getList()
    {
        $user_id = Auth::id();
        $record_by_month = WorkRecord::where('user_id', $user_id)
                ->get()
                ->groupBy(function($val) {
                    return Carbon::parse($val->created_at)->format('Y-m');
                });

        foreach($record_by_month as $key => $record_month){
            $total = 0;
            foreach($record_month as $record){
                $total += $record->commission_fee;
            }
            $record_by_month[$key] = $total;
        }

        return $record_by_month;
    }

    public function getDataMonthNow()
    {
        $now = Carbon::now()->format("Y-m");
        $work_records = $this->getList();
        $month_now = collect($work_records)->filter(function($value, $key) use($now){
            return $key == $now;
        })->first();

        return $month_now;
    }

    public function getByMonth($month)
    {
        $user_id = Auth::id();

        $work_records = $this->getList();

        $total_paid = collect($work_records)->filter(function($value, $key) use($month){
            return $key == $month;
        })->first();

        $record_by_month = WorkRecord::where('user_id', $user_id)
                ->where(function($query) use($month){
                    $query->whereYear('created_at', Carbon::parse($month)->format('Y'))
                          ->whereMonth('created_at', Carbon::parse($month)->format('m'));
                })
                ->get()
                ->groupBy(['work_id']);

        foreach ($record_by_month as $work_id => $records) {
            foreach ($records as $record){
                $record->work_title = Str::limit($record->work->title, 50);
                $record->gender = $record->worker->contact->sex ? 'Nam' : 'Nữ';
                $record->base_wage = number_format($record->base_wage) ?? 0;
                $record->transportation_fee = number_format($record->transportation_fee) ?? 0;
                $record->total_wage = number_format($record->total_wage) ?? 0;
                $record->commission_fee = number_format($record->commission_fee) ?? 0;
                $record->commission_fee_tax = number_format($record->commission_fee_tax) ?? 0;
                $record->work_date = Carbon::parse($record->work_date)->format('d/m/Y');
                $record->transfer_requested_at = Carbon::parse($record->transfer_requested_at)->format('d/m/Y');
                $record->transfered_at = Carbon::parse($record->transfered_at)->format('d/m/Y');
                $record->worktime_start_at= Carbon::parse($record->worktime_start_at)->format('H:i');
                $record->worktime_end_at = Carbon::parse($record->worktime_end_at)->format('H:i');
                $record->resttime_start_at = Carbon::parse($record->resttime_start_at)->format('H:i');
                $record->resttime_end_at = Carbon::parse($record->resttime_end_at)->format('H:i');
            }
        }

        $data = [
            'total_paid' => $total_paid,
            'date' =>  $this->dayInMonth($month),
            'record_by_month' => $record_by_month ?? [],
        ];

        return $data;
    }

    public function dayInMonth($month)
    {
        $month_now = Carbon::now()->format('Y-m');
        $month_format = Carbon::parse($month)->format('Y-m');
        
        if($month_format > $month_now){
            $date = 0;
        }
        elseif($month_format == $month_now){
            $date = Carbon::now()->day;
        }
        else{
            $date = Carbon::create($month)->daysInMonth;
        }
        return $date;
    }

    public function store($modify)
    {
        $work_application_id = WorkApplication::where('work_id', $modify->work_id)->where('worker_id', $modify->worker_id)->first()->id;
        $work = Work::findOrFail($modify->work_id);
        $worker = Worker::findOrFail($modify->worker_id);

        $work_worktime_start = Carbon::parse($work->worktime_start_at);
        $work_worktime_end = Carbon::parse($work->worktime_end_at);

        $wageCalculator = new WageCalculatorHelper(
            $work,
            $modify->modify_worktime_start_at,
            $modify->modify_worktime_end_at,
            $modify->resttime_minutes,
        );

        $base_worktime =  $work_worktime_start->diffInMinutes($work_worktime_end, false) - $modify->resttime_minutes;

        // $overtime_worktime = $this->totalOvetime($modify);
        $overtime_worktime = $wageCalculator->getOverTimeMinutes();

        // $nighttime_worktime = $this->getOverTimeNight($modify, $work->resttime_start_at, $work->resttime_end_at);
        $nighttime_worktime = $wageCalculator->getOverTimeNight();

        $ovetime_percentages =  config('const.overtime_percentage');
        $nighttime_percentages = config('const.nighttime_percentage');
        $ovetime_wage = ((($ovetime_percentages  *  $work->hourly_wage)/100  + $work->hourly_wage)  * $overtime_worktime)/60;
        $nighttime_wage = ((($nighttime_percentages * $work->hourly_wage)/100  + $work->hourly_wage) * $nighttime_worktime)/60;

        

        $record = new WorkRecord();
        $record->fill([
            'worker_id' => $modify->worker_id,
            'user_id' => $modify->home_id,
            'work_id' => $work->id,
            'company_id' => $modify->home->company_id,
            'work_application_id' => $work_application_id,
            'title' => $work->title,
            'work_date' => $modify->scheduled_worktime_start_at,
            'scheduled_worktime_start_at' => $modify->scheduled_worktime_start_at,
            'scheduled_worktime_end_at' => $modify->scheduled_worktime_end_at,
            'worktime_start_at' => $modify->modify_worktime_start_at,
            'worktime_end_at' => $modify->modify_worktime_end_at,
            'scheduled_resttime_start_at' => $work->resttime_start_at,
            'scheduled_resttime_end_at' => $work->resttime_end_at,
            'resttime_start_at' => null,
            'resttime_end_at' => null,
            'resttime_minutes' => $modify->resttime_minutes,
            'hourly_wage' => $work->hourly_wage,
            'base_worktime' => $base_worktime/60,
            'overtime_worktime' => $overtime_worktime/60,
            'nighttime_worktime' => $nighttime_worktime/60,
            'worker_first_name' => $worker->first_name,
            'worker_last_name' => $worker->last_name,
            'worker_first_name_kana' => $worker->first_name_kana,
            'worker_last_name_kana' => $worker->last_name_kana,
            'worker_sex' => $worker->sex,
            'base_wage' => $modify->base_wage,
            'ovetime_percentages' => $ovetime_percentages,
            'nighttime_percentages' => $nighttime_percentages,
            'ovetime_wage' => $ovetime_wage,
            'nighttime_wage' => $nighttime_wage,
            'transportation_fee' => $modify->transportation_fee,
            'total_wage' => $modify->base_wage + $ovetime_wage + $nighttime_wage + $modify->transportation_fee,
            'transfer_request_status' => 1,
            'transfer_requested_at' => null,
            'transfered_at' => null,
            'commission_fee'  => $wageCalculator->getCommissoinFee(),
            'commission_fee_tax' => $wageCalculator->getCommissoinFeeTax(),
            'commission_fee_tax_rate' => config('const.tax_rate'),
            'fixed_yn' => 'n',
            'fixed_at' => Carbon::now(),
        ])->save();

        return $record;
    }
}

