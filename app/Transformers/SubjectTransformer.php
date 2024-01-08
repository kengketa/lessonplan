<?php

namespace App\Transformers;

use App\Models\Subject;
use App\Models\User;
use League\Fractal\TransformerAbstract;

class SubjectTransformer extends TransformerAbstract
{
    public function transform(Subject $subject): array
    {
        $data = [
            'id' => $subject->id,
            'code' => $subject->code,
            'name' => $subject->name,
            'unit' => $subject->unit,
            'teacher_id' => $subject->pivot->teacher_id,
            'academic_year' => $subject->pivot->academic_year,
            'semester' => $subject->pivot->semester,
            'teacher' => fractal(User::find($subject->pivot->teacher_id), new UserTransformer())->toArray()
        ];
        return $data;
    }
}
