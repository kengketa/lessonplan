<?php

namespace App\Transformers;

use App\Models\Grade;
use League\Fractal\TransformerAbstract;

class GradeTransformer extends TransformerAbstract
{
    public function transform(Grade $grade): array
    {
        $data = [
            'id' => $grade->id,
            'school_id' => $grade->school_id,
            'type' => $grade->type,
            'level' => $grade->level,
            'name' => $grade->present()->name,
            'room_number' => $grade->room_number,
            'created_at' => $grade->present()->createdAt,
            'updated_at' => $grade->present()->updatedAt,
        ];

        return $data;
    }
}
