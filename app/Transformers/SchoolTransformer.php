<?php

namespace App\Transformers;

use App\Models\School;
use League\Fractal\TransformerAbstract;

class SchoolTransformer extends TransformerAbstract
{
    public function transform(School $school): array
    {
        $data = [
            'id' => $school->id,
            'name' => $school->name,
            'address' => $school->address,
            'subjects' => $school->subjects,
            'teachers' => $school->teachers->toArray(),
            'un_approved_reports_count' => $school->present()->unApprovedReportsCount,
            'created_at' => $school->present()->createdAt,
            'updated_at' => $school->present()->updatedAt,
        ];
        return $data;
    }
}
