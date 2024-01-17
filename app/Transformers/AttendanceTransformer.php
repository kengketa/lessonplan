<?php

namespace App\Transformers;

use App\Models\Attendance;
use League\Fractal\TransformerAbstract;

class AttendanceTransformer extends TransformerAbstract
{
    public function transform(Attendance $attendance): array
    {
        $data = [
            'id' => $attendance->id,
            'student_id' => $attendance->student_id,
            'grade_id' => $attendance->grade_id,
            'created_at' => $attendance->present()->createdAt,
        ];

        return $data;
    }
}
