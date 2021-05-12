<?php

namespace App\Helpers;
use Carbon\Carbon;

class DateHelper
{
    public static function formatDateToJapanese($stringDate = '')
    {
        $year = !empty($stringDate) ? date('Y', strtotime($stringDate)) : date('Y');
        $month = !empty($stringDate) ? date('m', strtotime($stringDate)) : date('m');
        $day = !empty($stringDate) ? date('d', strtotime($stringDate)) : date('d');
        $hours = !empty($stringDate) ? date('H', strtotime($stringDate)) : date('H');
        $minute = !empty($stringDate) ? date('i', strtotime($stringDate)) : date('i');

        return $year. '年 '. $month. '月 '. $day. '日 \n '. $hours.':'. $minute;
    }

    public static function compareDateTime($startDate, $endDate) {
        return Carbon::parse($startDate)->diffInMinutes($endDate, false);
    }

    /**
     * 指定日付の曜日を取得します
     * @param $string 対象日付
     * @return mixed
     */
    public static function getShortJpDay(string $date)
    {
        $weekOfDay = ['日', '月', '火', '水', '木', '金', '土'];
        $dateTime = date_create($date);
        return $weekOfDay[(int)date_format($dateTime, 'w')];
    }
}
