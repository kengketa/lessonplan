<?php

namespace App\Transformers;

use App\Models\Student;
use League\Fractal\TransformerAbstract;

class StudentTransformer extends TransformerAbstract
{
    public function transform(Student $student): array
    {
        $data = [
            'school_id' => $student->school_id,
            'id' => $student->id,
            'number' => $student->number,
            'prefix' => $student->prefix,
            'first_name' => $student->first_name,
            'last_name' => $student->last_name,
            'nick_name' => $student->nick_name,
            'email' => $student->email,
            'phone' => $student->phone,
            'created_at' => $student->present()->createdAt,
            'updated_at' => $student->present()->updatedAt,
        ];
        return $data;
    }
}
