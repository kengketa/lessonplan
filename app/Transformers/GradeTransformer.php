<?php

namespace App\Transformers;

use App\Models\Grade;
use League\Fractal\TransformerAbstract;

class GradeTransformer extends TransformerAbstract
{
    public function transform(Grade $grade): array
    {
        $grade->load('school');
        $data = [
            'id' => $grade->id,
            'school' => $grade->school->toArray(),
            'full_name' => $grade->fullName,
            'name' => $grade->present()->name,
            'created_at' => $grade->present()->createdAt,
            'updated_at' => $grade->present()->updatedAt,
        ];

        return $data;
    }
}
