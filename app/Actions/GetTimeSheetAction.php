<?php

namespace App\Actions;

use App\Models\ClockIn;
use App\Models\User;
use App\Transformers\ClockInTransformer;
use Carbon\Carbon;
use Illuminate\Support\Str;

class GetTimeSheetAction
{
    public function execute(User $teacher, $month, $year)
    {
        $clockIns = ClockIn::where('teacher_id', $teacher->id)
            ->whereYear('date', $year)
            ->whereMonth('date', $month)
            ->orderBy('date')
            ->get();
        $lastDayOfTheMonth = Carbon::parse($year.'-'.$month.'-'.'1')->daysInMonth;
        $clockInData = [];
        for ($i = 1; $i <= $lastDayOfTheMonth; $i++) {
            $date = $year.'-'.$month.'-'.$i;
            $clockedIn = $clockIns->filter(function ($q) use ($i) {
                return (int)Carbon::parse($q->date)->format('d') == $i;
            });
            $clockInData[$i]['date'] = $i;
            $clockInData[$i]['month'] = $month;
            $clockInData[$i]['year'] = $year;
            $clockInData[$i]['day'] = Carbon::parse($date)->format('D');
            $clockInData[$i]['displayDate'] = Carbon::parse($date)->format('D d M Y');
            $clockInData[$i]['clockedIn'] = null;

            if (count($clockedIn) > 0) {
                $clockInData[$i]['clockedIn'] = fractal($clockedIn, new ClockInTransformer())->toArray()['data'][0];
            }
        }
        return $clockInData;
    }
}
