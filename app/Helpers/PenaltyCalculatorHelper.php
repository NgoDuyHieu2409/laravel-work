<?php


namespace App\Helpers;


use App\Work;
use App\WorkApplication;
use Carbon\Carbon;

class PenaltyCalculatorHelper
{
    public static function getMinusPoint(WorkApplication $workApplication, Work $work)
    {
        $point = 0;
        $cancelAt = Carbon::parse($workApplication->canceled_at);
        $workTimeStartAt = Carbon::parse($work->worktime_start_at);
        $hours_from_cancel_to_recruit_start = $cancelAt->floatDiffInHours($workTimeStartAt, false);
        
        // c'anceled_at' is less than or equal to 72 hours before 'deadline_at'
        if ($hours_from_cancel_to_recruit_start <= 72 && $hours_from_cancel_to_recruit_start > 24) {
            $point = 1;
        }

        if ($hours_from_cancel_to_recruit_start <= 24 && $hours_from_cancel_to_recruit_start > 12) {
            $point = 4;
        }

        if ($hours_from_cancel_to_recruit_start <= 12 && $hours_from_cancel_to_recruit_start > 8) {
            $point = 6;
        }

        if ($hours_from_cancel_to_recruit_start <= 8 && $hours_from_cancel_to_recruit_start > 4) {
            $point = 7;
        }

        if ($hours_from_cancel_to_recruit_start <= 4) {
            $point = 8;
        }

        return $point;
    }
}
