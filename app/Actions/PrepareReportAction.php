<?php

namespace App\Actions;

use App\Models\Grade;
use App\Models\Report;
use App\Models\School;
use App\Transformers\GradeTransformer;
use App\Transformers\MisbehaviorTransformer;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class PrepareReportAction
{
    public function execute(School $school, $existReport = null): array
    {
        if ($existReport) {
            $report = $this->prepareExistReport($existReport);
            return $report;
        }
        $report['teacher_name'] = Auth::user()->name;
        $report['approver'] = null;
        $report['school'] = $school->toArray();
        $report['all_grades'] = fractal($school->grades, new GradeTransformer())->toArray()['data'];
        $report['all_subjects'] = $school->subjects;
        $report['types'] = [
            ['id' => Report::TOPIC_PHONIC, 'name' => 'phonic'],
            ['id' => Report::TOPIC_LEARNING_AREA, 'name' => 'learning area'],
        ];
        $report['for_grades'] = [];
        $report['plans'] = [];
        $report['creator_id'] = Auth::user()->id;
        return $report;
    }

    private function prepareExistReport(Report $report)
    {
        $report->load('creator', 'approver', 'grade.school.grades', 'misbehaviorStudents');
        $newReport['id'] = $report->id;
        $newReport['teacher_name'] = $report->creator ? $report->creator->name : 'undefined';
        $newReport['approver'] = $report->approver ? $report->approver->toArray() : null;
        $newReport['week_number'] = $report->week_number;
        $newReport['lesson_number'] = $report->lesson_number;
        $newReport['subject'] = $report->subject;
        $newReport['school'] = $report->grade->school->toArray();
        $newReport['all_grades'] = fractal($report->grade->school->grades, new GradeTransformer())->toArray()['data'];
        $newReport['all_subjects'] = $newReport['school']['subjects'];
        
        $newReport['types'] = [
            ['id' => Report::TOPIC_PHONIC, 'name' => 'phonic'],
            ['id' => Report::TOPIC_LEARNING_AREA, 'name' => 'learning area'],
        ];
        $grade['id'] = $report->grade_id;
        $grade['date'] = $report->date ? Carbon::parse($report->date)->format('d-m-Y') : null;
        $newReport['for_grades'] = [$grade];
        foreach ($report->plans as $plan) {
            $newPlan['typing_vocab'] = null;
            $newPlan['type'] = $plan['type'];
            $newPlan['topic'] = $plan['topic'];
            $newPlan['vocabs'] = $plan['vocabs'];
            $newPlan['details'] = $plan['details'];
            $newReport['plans'][] = $newPlan;
        }
        $newReport['teaching_materials'] = $report->teaching_materials;
        $newReport['activities'] = $report->activities;
        $newReport['outcome'] = $report->outcome;
        $newReport['outstanding_students'] = $report->outstanding_students;
        $newReport['need_improvement_students'] = $report->need_improvement_students;
        $newReport['misbehavior_students'] = $report->misbehaviorStudents->toArray();
        $newReport['misbehavior_students'] = fractal(
            $report->misbehaviorStudents,
            new MisbehaviorTransformer()
        )->toArray()['data'];
        $newReport['creator_id'] = $report->creator->id;
        $newReport['created_at'] = $report->present()->createdAt;
        $newReport['updated_at'] = $report->present()->updatedAt;
        //dd($newReport['plans'][0]['details']);
        return $newReport;
    }

}
