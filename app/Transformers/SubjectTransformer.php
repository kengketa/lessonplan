<?php

namespace App\Transformers;

use App\Models\Subject;
use League\Fractal\TransformerAbstract;

class SubjectTransformer extends TransformerAbstract
{
    public function transform(Subject $subject): array
    {
        $data = [
            'id'	=>	$subject->id,
			'code'	=>	$subject->code,
			'name'	=>	$subject->name,
			'unit'	=>	$subject->unit,
			'created_at'	=>	$subject->present()->createdAt,
			'updated_at'	=>	$subject->present()->updatedAt,
        ];

        return $data;
    }
}
