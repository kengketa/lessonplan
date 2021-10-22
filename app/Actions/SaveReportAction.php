<?php

namespace App\Actions;

use App\Models\Report;
use App\Models\School;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class SaveReportAction
{
    protected Report $report;

    public function execute(Report $report, School $school, array $data)
    {
        $this->report = $report;

        //update case
//        if (!empty($this->report->id)) {
//            $this->report->update($data);
//            return $this->report;
//        }

        //create case
        $grades = $data['report']['for_grades'];
        $plans = [];
        foreach ($data['report']['plans'] as $key => $plan) {
            $plans[$key]['type'] = $plan['type'];
            $plans[$key]['topic'] = $plan['topic'];
            $plans[$key]['vocabs'] = $plan['vocabs'];
            $plans[$key]['details'] = $plan['details'];
        }
        $addedReports = [];
        foreach ($grades as $grade) {
            $newReport['grade_id'] = $grade['id'];
            $newReport['date'] = Carbon::parse($grade['date'])->format('Y-m-y');
            $newReport['academic_year'] = 2021;
            $newReport['semester'] = 2;
            $newReport['week_number'] = $data['week_number'];
            $newReport['lesson_number'] = $data['lesson_number'];
            $newReport['plans'] = $plans;
            $newReport['subject'] = $data['subject'];
            $newReport['creator'] = Auth::id();
            $addedReports[] = Report::create($newReport);
        }
        return $addedReports;
    }

}
