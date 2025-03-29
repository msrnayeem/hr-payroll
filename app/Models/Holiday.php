<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Support\Carbon;

class Holiday extends Model
{
    protected $fillable = ['name', 'from_date', 'to_date', 'status'];

    public static function getHolidaysForMonth($month, $year)
    {
        $month = is_numeric($month) ? str_pad($month, 2, '0', STR_PAD_LEFT) : date('m', strtotime("1 $month $year"));
        $start = "$year-$month-01";
        $end = date('Y-m-t', strtotime($start));

        $holidays = self::where('from_date', '<=', $end)
            ->where('to_date', '>=', $start)
            ->where('status', 'active')
            ->get();

        $days = [];
        foreach ($holidays as $holiday) {
            $current = max(strtotime($holiday->from_date), strtotime($start));
            $last = min(strtotime($holiday->to_date), strtotime($end));

            for (; $current <= $last; $current = strtotime('+1 day', $current)) {
                $date = date('Y-m-d', $current);
                $days[$date] = date('l', $current);
            }
        }

        ksort($days);
        return  $days;
    }
}
