<?php


namespace App\Helpers;


use Carbon\Carbon;

class QueryHelper
{
    public static function handleDateTimeRequest($query, $dateField = 'date_time', $hourField = 'hour')
    {
        if ($startDate = request('start_date')) {
            $query->where($dateField, '>=', Carbon::parse($startDate));
        }

        if ($endDate = request('end_date')) {
            $query->where($dateField, '<=', Carbon::parse($endDate)->endOfDay());
        }

        if ($startingHour = request('starting_hour')) { // 0 can be ignored because >= 0 (after 12am) means all orders
            $query->where($hourField, '>=', intval($startingHour));
        }

        if ($endingHour = request('ending_hour')) { // 0 can be ignored because < 0 (before 12am) means all orders
            $query->where($hourField, '<', intval($endingHour));
        }

        return $query;
    }
}
