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

    public function execute(Report $report, School $school, array $data): array
    {
        $this->report = $report;
        if ($this->report->id) {
            $updatedReports = $this->updateReport($data);
            return $updatedReports;
        }
        $createdReports = $this->createReports($data);
        return $createdReports;
    }

    private function updateReport($data): array
    {
        $plans = [];
        foreach ($data['report']['plans'] as $key => $plan) {
            $plans[$key]['type'] = $plan['type'];
            $plans[$key]['topic'] = $plan['topic'];
            $plans[$key]['vocabs'] = $plan['vocabs'];
            $plans[$key]['details'] = $plan['details'];
        }
        $this->report->grade_id = $data['report']['for_grades'][0]['id'];
        $this->report->date = Carbon::parse($data['report']['for_grades'][0]['date'])->format('Y-m-d');
        $this->report->week_number = $data['week_number'];
        $this->report->lesson_number = $data['lesson_number'];
        $this->report->plans = $plans;
        $this->report->subject = $data['subject'];
        $this->report->updated_at = Carbon::now();
        $this->report->approver_id = null;
        $this->report->save();
        $updatedReport = new PrepareReportAction();
        $updatedReportData = $updatedReport->execute($this->report->grade->school, $this->report);
        return $updatedReportData;
    }

    private function createReports($data): array
    {
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
            $newReport['academic_year'] = getCurrentAcademicYear();
            $newReport['semester'] = getCurrentSemester();
            $newReport['week_number'] = $data['week_number'];
            $newReport['lesson_number'] = $data['lesson_number'];
            $newReport['plans'] = $plans;
            $newReport['subject'] = $data['subject'];
            $newReport['creator_id'] = Auth::id();
            $newReport['approver_id'] = null;
            $addedReports[] = Report::create($newReport);
        }
        return $addedReports;
    }

}
