<?php

namespace App\Actions;

use App\Models\GlobalReport;
use App\Models\Report;
use App\Models\School;
use App\Models\User;
use App\Transformers\UserTransformer;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use phpDocumentor\Reflection\Types\Integer;


class BulkApproveAction
{
    protected Report $report;

    public function execute(Collection $reports, int $selectedWeek): array
    {
        $tobeApprovedReports = $reports->filter(function ($report) {
            $checked = true;
            $plans = $report->plans;
            foreach ($plans as $plan) {
                if (strlen($plan['topic']) < 5) {
                    $checked = false;
                }
//                if(count($plan['vocabs']) == 0){
//                    $checked = false;
//                }
                if (strlen($plan['details']) < 100) {
                    $checked = false;
                }
            }
//            if(strlen($report->teaching_materials) < 10){
//                $checked = false;
//            }
//            if(strlen($report->activities) < 10){
//                $checked = false;
//            }
            // case ngim doesnt need to check outcome
            if (strlen($report->outcome) < 30 && $report->grade->school->id != 1) {
                $checked = false;
            }
            return $checked;
        })->values();
        $tobeApprovedReportIds = $tobeApprovedReports->pluck('id')->toArray();
        $teachersIds = array_keys($tobeApprovedReports->groupBy('creator_id')->toArray());
        $approverId = Auth::id();
        $approvedReports = Report::whereIn('id', $tobeApprovedReportIds)->update(['approver_id' => $approverId]);
        return $this->generateReportLinks($teachersIds, $selectedWeek);
    }

    private function generateReportLinks(array $teachersIds, int $selectedWeek): array
    {
        $toGenerateLinkTeachers = [];
        $teachers = User::whereIn('id', $teachersIds)->get();
        if (!$teachers) {
            return [];
        }
        foreach ($teachers as $teacher) {
            $unApprovedReportCount = Report::where('creator_id', $teacher->id)
                ->where('academic_year', getCurrentAcademicYear())
                ->where('semester', getCurrentSemester())
                ->where('week_number', $selectedWeek)
                ->whereNull('approver_id')
                ->count();
            $toGenerateLinkTeachers[$teacher->id]['teacher'] = fractal($teacher, new UserTransformer())->toArray();
            if ($unApprovedReportCount == 0) {
                $toGenerateLinkTeachers[$teacher->id]['done'] = true;
            }
            if ($unApprovedReportCount > 0) {
                $toGenerateLinkTeachers[$teacher->id]['done'] = false;
            }
        }
        foreach ($toGenerateLinkTeachers as $teacherId => $generateLinkTeacher) {
            $reportIds = Report::where('creator_id', $teacherId)
                ->where('academic_year', getCurrentAcademicYear())
                ->where('semester', getCurrentSemester())
                ->where('week_number', $selectedWeek)
                ->whereNotNull('approver_id')
                ->pluck('id')
                ->toArray();
            $link = URL::to('/') . '/global-reports?';
            $i = 0;
            foreach ($reportIds as $id) {
                $link = $link . 'reportIds%5B' . $i . '%5D=' . $id . '&';
                $i++;
            }
            if ($generateLinkTeacher['done'] == false) {
                $toGenerateLinkTeachers[$teacherId]['link'] = null;
            }
            if ($generateLinkTeacher['done'] == true) {
                $toGenerateLinkTeachers[$teacherId]['link'] = rtrim($link, "&");
            }
        }
        foreach ($toGenerateLinkTeachers as $id => $record) {
            if ($record['done'] == false) {
                $toGenerateLinkTeachers[$id]['globalLink'] = null;
                continue;
            }
            $foundDuplicated = GlobalReport::where('link', $record['link'])->first();
            if ($foundDuplicated) {
                $toGenerateLinkTeachers[$id]['globalLink'] = route('reports.global.show', $foundDuplicated->hashid);
                continue;
            }
            $newGlobalLink = GlobalReport::create([
                'link' => $record['link'],
                'creator_id' => Auth::id()
            ]);
            $toGenerateLinkTeachers[$id]['globalLink'] = route('reports.global.show', $newGlobalLink->hashid);
        }
        return $toGenerateLinkTeachers;
    }
}
