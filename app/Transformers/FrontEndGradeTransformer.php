<?php

namespace App\Transformers;

use App\Models\Grade;
use League\Fractal\TransformerAbstract;

class FrontEndGradeTransformer extends TransformerAbstract
{
    public function transform(Grade $grade): array
    {
        $data = [
            'id' => $grade->id,
            'name' => $grade->present()->name,
            'created_at' => $grade->present()->createdAt,
            'updated_at' => $grade->present()->updatedAt,
        ];

        return $data;
    }
}
