<?php

namespace App\Transformers;

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
            'academic_year' => $report->academic_year,
            'semester' => $report->semester,
            'week_number' => $report->week_number,
            'lesson_number' => $report->lesson_number,
            'date' => $report->present()->date,
            'plans' => $report->present()->plans,
            'topics' => $report->present()->topics,
            'subject' => $report->present()->subject,
            'outcome' => $report->outcome,
            'outstanding_student' => $report->outstanding_student,
            'need_improvement_student' => $report->need_improvement_student,
            'creator' => $report->creator,
            'approver' => $report->approver,
            'created_at' => $report->present()->createdAt,
            'updated_at' => $report->present()->updatedAt,
        ];

        return $data;
    }
}
