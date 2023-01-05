<?php

namespace App\Transformers;

use App\Models\Misbehavior;
use App\Models\Report;
use League\Fractal\TransformerAbstract;

class ReportTransformer extends TransformerAbstract
{
    public function transform(Report $report): array
    {
        $data = [
            'id' => $report->id,
            'grade_id' => $report->grade_id,
            'grade_name' => $report->grade->present()->name,
            'teacher_name' => $report->creator->name,
            'school_name' => $report->grade->school->name,
            'academic_year' => $report->academic_year,
            'semester' => $report->semester,
            'week_number' => $report->week_number,
            'lesson_number' => $report->lesson_number,
            'date' => $report->present()->date,
            'plans' => $report->present()->plans,
            'topics' => $report->present()->topics,
            'subject' => $report->present()->subject,
            'teaching_materials' => $report->teaching_materials,
            'activities' => $report->activities,
            'outcome' => $report->outcome,
            'outstanding_students' => $report->outstanding_students,
            'need_improvement_students' => $this->getNeedImprovementStudents($report),
            'misbehavior_students' => $report->misbehaviorStudents,
            'creator' => $report->creator?->toArray(),
            'approver' => $report->approver?->toArray(),
            'created_at' => $report->present()->createdAt,
            'updated_at' => $report->present()->updatedAt,
        ];
        return $data;
    }

    private function getNeedImprovementStudents($report)
    {
        if ($report->need_improvement_students != null) {
            return $report->need_improvement_students;
        }
        $misbehaviorStudents = Misbehavior::where('report_id', $report->id)->get();
        $needImprovementStr = "";
        foreach ($misbehaviorStudents as $student) {
            $needImprovementStr = $needImprovementStr.''.$student->name.':'.$student->behavior."<br>";
        }
        return $needImprovementStr;
    }
}
