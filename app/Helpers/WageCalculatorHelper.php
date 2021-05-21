<?php


namespace App\Helpers;


use Carbon\Carbon;

class WageCalculatorHelper
{
    private $commissoinPercent;
    private $commissoinFeeTaxRate;

    private $transportationFee;
    private $workTimeStartAt;
    private $workTimeEndAt;
    private $scheduledWorktimeStartAt;
    private $scheduledWorktimeEndAt;
    private $restTimeMinutes;
    private $hourlyWage;
    private $ovetimePercentages;
    private $nighttimePercentages;


    private $time5h;
    private $time22h;

    private $WorkResttimeStart;
    private $WorkResttimeEnd;

     /**
     *
     * @param datetime $worktime_start_at  //$modify_worktime_start_at,
     * @param datetime $worktime_end_at    //$modify_worktime_end_at,
     * @param double $resttime_minutes     //$modify_resttime_minutes || $work->resttime_minutes,
     */
    public function __construct(
        $work,
        $worktime_start_at,  //$modify_worktime_start_at,
        $worktime_end_at,    //$modify_worktime_end_at,
        $resttime_minutes    //$resttime_minutes,
    ){
        $this->commissoinPercent = Config('const.commissoin_percent');
        $this->commissoinFeeTaxRate = Config('const.tax_rate');

        $this->workTimeStartAt = $worktime_start_at;
        $this->workTimeEndAt = $worktime_end_at;
        $this->scheduledWorktimeStartAt = $work->worktime_start_at;
        $this->scheduledWorktimeEndAt = $work->worktime_end_at;
        $this->restTimeMinutes = $resttime_minutes;
        $this->hourlyWage = $work->hourly_wage;
        $this->transportationFee = $work->transportation_fee;
        $this->ovetimePercentages = $work->ovetime_extra_percentages;
        $this->nighttimePercentages = $work->night_extra_percentages;

        $workTimeEnd = Carbon::parse($this->workTimeEndAt);
        $workTimeStart = Carbon::parse($this->workTimeStartAt);
        $this->time5h = Carbon::parse("$workTimeEnd->year-$workTimeEnd->month-$workTimeEnd->day 05:00:00")->addDay();
        $this->time22h = Carbon::parse("$workTimeStart->year-$workTimeStart->month-$workTimeStart->day 22:00:00");

        $this->WorkResttimeStart = $work->resttime_start_at;
        $this->WorkResttimeEnd = $work->resttime_end_at;

    }

    public function getTotalWage()
    {
        return $this->getBaseWage() + $this->transportationFee + $this->getOverTimeWage() + $this->getNightTimeWage();
    }

    public function getCommissoinFee()
    {
        return $this->getTotalWage() * ($this->commissoinPercent / 100);
    }

    public function getCommissoinFeeTax()
    {
        return $this->getCommissoinFee() * ($this->commissoinFeeTaxRate / 100);
    }

    public function getBaseWage()
    {
        return max(($this->getTotalWorkingBaseMinutes() / 60) * $this->hourlyWage, 0);
    }

    public function getTotalWorkingBaseMinutes()
    {
        return max($this->getTotalWorkingTimeMinutes() - $this->getOverTimeMinutes() - $this->getTotalNightTimeMinutes(), 0);
    }


    public function getTotalWorkingTimeMinutes()
    {
        return max((Carbon::parse($this->workTimeStartAt)->diffInMinutes($this->workTimeEndAt, false)) - $this->restTimeMinutes, 0);
    }

    public function getTotalScheduledWorkingTimeMinutes()
    {
        $scheduledTimeMinutes = Carbon::parse($this->scheduledWorktimeStartAt)->diffInMinutes($this->scheduledWorktimeEndAt, false) - $this->restTimeMinutes;
        return max($scheduledTimeMinutes, 0);
    }

    public function getOverTimeMinutes()
    {
        // $scheduledWorktimeEndAt = Carbon::parse($this->scheduledWorktimeEndAt);
        // $workTimeEnd = Carbon::parse($this->workTimeEndAt);
        // if ($scheduledWorktimeEndAt->diffInMinutes($workTimeEnd, false) > 0) {
        //     if ($workTimeEnd->lessThan($this->time5h)) {
        //         return 0;
        //     }
        //     // before 22h
        //     if ($workTimeEnd->lt($this->time22h)) {
        //         return $scheduledWorktimeEndAt->diffInMinutes($workTimeEnd);
        //     } else // after 22h move to nighttime
        //     {
        //         return $scheduledWorktimeEndAt->diffInMinutes($this->time22h);
        //     }
        // }

        $scheduledWorktimeEndAt = Carbon::parse($this->scheduledWorktimeEndAt);
        $workTimeEnd            = Carbon::parse($this->workTimeEndAt);
        $time22h                = $this->time22h;
        $total_time             = 0;

        if($scheduledWorktimeEndAt->lt($workTimeEnd)){
            if($scheduledWorktimeEndAt->lt($time22h)){
                if($workTimeEnd->lt($time22h)){
                    $total_time = $scheduledWorktimeEndAt->diffInMinutes($workTimeEnd, false);
                }
                else{
                    $total_time = $scheduledWorktimeEndAt->diffInMinutes($time22h, false);
                }
            }
        }

        return $total_time;
    }

    public function getOverTimeWage()
    {
        $overtime_hourly_wage = $this->hourlyWage + (($this->hourlyWage * $this->ovetimePercentages) / 100);
        return ($this->getOverTimeMinutes() / 60) * $overtime_hourly_wage;
    }

    public function getTotalNightTimeMinutes()
    {

        $workTimeStartAt = Carbon::parse($this->workTimeStartAt);
        $nextDay = Carbon::parse($this->workTimeStartAt)->addDay();
        $overNightTime = ["$workTimeStartAt->year-$workTimeStartAt->month-$workTimeStartAt->day 22:00:00", "$nextDay->year-$nextDay->month-{$nextDay->day}  05:00:00"];
        $overNightTime2 = ["$workTimeStartAt->year-$workTimeStartAt->month-$workTimeStartAt->day 00:00:00", "$workTimeStartAt->year-$workTimeStartAt->month-$workTimeStartAt->day 05:00:00"];
        $times = [$this->workTimeStartAt, $this->workTimeEndAt];
        $overNight = $this->getOverlap($times, $overNightTime);
        $overNight2 = $this->getOverlap($times, $overNightTime2);
        return min($overNight + $overNight2, $this->getTotalWorkingTimeMinutes());
    }

    public function getNightTimeWage()
    {
        $nighttime_hourly_wage = $this->hourlyWage + (($this->hourlyWage * $this->nighttimePercentages) / 100);
        return ($this->getTotalNightTimeMinutes() / 60) * $nighttime_hourly_wage;
    }

    private function getOverlap($arr1, $arr2)
    {

        $arr1 = array_map(function ($e) {
            return strtotime($e);
        }, $arr1);
        $arr2 = array_map(function ($e) {
            return strtotime($e);
        }, $arr2);

        $array = array_merge($arr1, $arr2);

        sort($array);

        $start = $array[0];
        $end = $array[count($array) - 1];


        $total = $end - $start;
        $x1 = $arr1[0] - $arr2[0];
        $x2 = $arr1[1] - $arr2[1];

        $x1 = $x1 > 0 ? $x1 : -$x1;
        $x2 = $x2 > 0 ? $x2 : -$x2;

        $val = $total - $x1 - $x2;
        return $val > 0 ? $val / 60 : 0;
    }

    public function getOverTimeNight()
    {
        $workTimeStart = Carbon::parse($this->workTimeStartAt); 
        $workTimeEnd   = Carbon::parse($this->workTimeEndAt); 
        $time22h = $this->time22h;
        $time5h = $this->time5h;

        $night_resttime = $this->totalResttimeTimeNight($this->WorkResttimeStart, $this->WorkResttimeEnd, $time22h, $time5h);

        $night_time = 0;

        if($time22h->lt($workTimeStart) && $time22h->lt($workTimeEnd)){
            if($workTimeEnd->lt($time5h)){
                $night_time = $workTimeStart->diffInMinutes($workTimeEnd) - $night_resttime;
            }
            else{
                $night_time = $workTimeStart->diffInMinutes($time5h) - $night_resttime;
            }
        }
        elseif(!$time22h->lt($workTimeStart) && $time22h->lt($workTimeEnd)){

            if($workTimeEnd->lt($time5h)){
                $night_time = $time22h->diffInMinutes($workTimeEnd) - $night_resttime;

            }
            else{
                $night_time = $time22h->diffInMinutes($time5h) - $night_resttime;
            }
        }
        return $night_time;
    }

    private function totalResttimeTimeNight($work_resttime_start, $work_resttime_end, $time22h, $time5h)
    {
        $time = 0;
        if($work_resttime_start && $work_resttime_end){
            $work_resttime_start = Carbon::parse($this->WorkResttimeStart);
            $work_resttime_end = Carbon::parse($this->WorkResttimeEnd);
            if($time22h->lt($work_resttime_start) && $time22h->lt($work_resttime_end)){
                if($work_resttime_end->lt($time5h)){
                    $time = $work_resttime_start->diffInMinutes($work_resttime_end);
                }
                else{
                    $time = $work_resttime_start->diffInMinutes($time5h);
                }
            }
            elseif(!$time22h->lt($work_resttime_start) && $time22h->lt($work_resttime_end)){
                if($work_resttime_end->lt($time5h)){
                    $time = $time22h->diffInMinutes($work_resttime_end);
                }
                else{
                    $time = $time22h->diffInMinutes($time5h);
                }
            }
        }

        return $time;
    }
}
