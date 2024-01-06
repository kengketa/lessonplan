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
            'code' => $student->code,
            'prefix' => $student->prefix,
            'first_name' => $student->first_name,
            'last_name' => $student->last_name,
            'email' => $student->email,
            'password' => $student->password,
            'phone' => $student->phone,
            'created_at' => $student->present()->createdAt,
            'updated_at' => $student->present()->updatedAt,
        ];
        return $data;
    }
}
