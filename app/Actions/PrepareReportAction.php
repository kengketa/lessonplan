<?php

namespace App\Actions;

use App\Models\Grade;
use App\Models\Report;
use App\Models\School;
use App\Transformers\GradeTransformer;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PrepareReportAction
{
    public function execute(School $school, $existReport = null): array
    {
        if ($existReport) {
            $report = $this->prepareExistReport($existReport);
            return $report;
        }
        $report['school'] = $school->toArray();
        $report['all_grades'] = fractal($school->grades, new GradeTransformer())->toArray()['data'];
        $report['all_subjects'] = $school->subjects;
        $report['types'] = [
            ['id' => Report::TOPIC_PHONIC, 'name' => 'phonic'],
            ['id' => Report::TOPIC_LEARNING_AREA, 'name' => 'learning area'],
        ];
        $report['for_grades'] = [];
        $report['plans'] = [];
        return $report;
    }

    private function prepareExistReport(Report $report)
    {
        $newReport['id'] = $report->id;
        $newReport['week_number'] = $report->week_number;
        $newReport['lesson_number'] = $report->lesson_number;
        $newReport['subject'] = $report->subject;
        $newReport['school'] = $report->grade->school->toArray();
        $newReport['all_grades'] = fractal($report->grade->school->grades, new GradeTransformer())->toArray()['data'];
        $newReport['all_subjects'] = $report->grade->school->subjects;
        $newReport['types'] = [
            ['id' => Report::TOPIC_PHONIC, 'name' => 'phonic'],
            ['id' => Report::TOPIC_LEARNING_AREA, 'name' => 'learning area'],
        ];
        $grade['id'] = $report->grade_id;
        $grade['date'] = Carbon::parse($report->date)->format('d-m-Y');
        $newReport['for_grades'] = [$grade];
        foreach ($report->plans as $plan) {
            $newPlan['typing_vocab'] = null;
            $newPlan['type'] = $plan['type'];
            $newPlan['topic'] = $plan['topic'];
            $newPlan['vocabs'] = $plan['vocabs'];
            $newPlan['details'] = $plan['details'];
            $newReport['plans'][] = $newPlan;
        }
        return $newReport;
    }

}
