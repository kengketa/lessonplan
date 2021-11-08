<?php

use App\Models\DailyReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\ClockIn;

function snakeCaseToText($text)
{
    return implode(' ', explode('_', $text));
}

function getYears()
{
    $years = [
        ['id' => 0, 'name' => 'Current ('.getCurrentAcademicYear().')'],
        ['id' => 2019, 'name' => '2019'],
        ['id' => 2020, 'name' => '2020'],
        ['id' => 2021, 'name' => '2021'],
        ['id' => 2022, 'name' => '2022'],
        ['id' => 2023, 'name' => '2023'],
        ['id' => 2024, 'name' => '2024'],
        ['id' => 2025, 'name' => '2025'],
        ['id' => 2026, 'name' => '2026'],
        ['id' => 2027, 'name' => '2027'],
        ['id' => 2028, 'name' => '2028'],
        ['id' => 2029, 'name' => '2029'],
        ['id' => 20230, 'name' => '2030'],
    ];
    return $years;
}

function getWeeks()
{
    $weeks = [];
    $start = 1;
    $end = 40;
    if (getCurrentSemester() == 1) {
        $start = 1;
        $end = 20;
    }
    if (getCurrentSemester() == 2) {
        $start = 21;
        $end = 40;
    }
    for ($i = $start; $i <= $end; $i++) {
        $newWeek['id'] = $i;
        $newWeek['name'] = (string)$i;
        $weeks[] = $newWeek;
    }
    return $weeks;
}

function getClockInMonthList()
{
    $monthOptions = [];
    $clockIns = ClockIn::count();
    if ($clockIns == 0) {
        return $monthOptions;
    }

    $clockInGroupByMonthYear = DB::table('clock_ins')
        ->select(
            DB::raw('count(id) as total'),
            DB::raw('year(date) as year'),
            DB::raw('month(date) as month'))
        ->groupBy('year', 'month')
        ->orderBy('year', 'desc')
        ->orderBy('month', 'desc')
        ->get();


    foreach ($clockInGroupByMonthYear as $index => $clockIn) {
        $monthOptions[$index]['id'] = Carbon::parse($clockIn->year.'-'.$clockIn->month.'-'.'1')->format('Y-m');
        $monthOptions[$index]['name'] = Carbon::parse($clockIn->year.'-'.$clockIn->month.'-'.'1')->format('F - Y');
    }
    return $monthOptions;
}

function getSemesters()
{
    $semester = [
        ['id' => 0, 'name' => 'Current ('.getCurrentSemester().')'],
        ['id' => 1, 'name' => 'semseter 1'],
        ['id' => 2, 'name' => 'semester 2'],
    ];
    return $semester;
}

function getCurrentAcademicYear()
{
    $settings = \App\Models\Setting::first()->settings;
    return (integer)$settings['current_academic_year'];
}

function getCurrentSemester()
{
    $settings = \App\Models\Setting::first()->settings;
    return (integer)$settings['current_semester'];
}
