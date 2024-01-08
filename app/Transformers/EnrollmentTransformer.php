<?php

namespace App\Transformers;

use App\Models\Enrollment;
use League\Fractal\TransformerAbstract;

class EnrollmentTransformer extends TransformerAbstract
{
    public function transform(Enrollment $enrollment): array
    {
        $data = [
            'id' => $enrollment->id,
            'grade_id' => $enrollment->grade_id,
            'academic_year' => $enrollment->academic_year,
            'student_id' => $enrollment->student_id,
            'semester' => $enrollment->semester,
            'student' => fractal($enrollment->student, new StudentTransformer())->toArray(),
            'created_at' => $enrollment->present()->createdAt,
            'updated_at' => $enrollment->present()->updatedAt,
        ];
        return $data;
    }
}
