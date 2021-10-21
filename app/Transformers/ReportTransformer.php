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
            'academic_year' => $report->academic_year,
            'semester' => $report->semester,
            'week_number' => $report->week_number,
            'lesson_number' => $report->lesson_number,
            'date' => $report->present()->date,
            'topic' => $report->topic,
            'subject' => $report->subject,
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
