<?php

namespace App\Transformers;

use App\Models\CurrentGrade;
use League\Fractal\TransformerAbstract;

class CurrentGradeTransformer extends TransformerAbstract
{
    public function transform(CurrentGrade $currentGrade): array
    {
        $data = [
            'id' => $currentGrade->id,
            'grade_id' => $currentGrade->grade_id,
            'academic_year' => $currentGrade->academic_year,
            'semester' => $currentGrade->semester,
            'subject' => $currentGrade->subject->toArray(),
            'teacher' => fractal($currentGrade->teacher, new UserTransformer())->toArray()
        ];

        return $data;
    }
}
