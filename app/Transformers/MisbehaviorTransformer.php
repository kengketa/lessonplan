<?php

namespace App\Transformers;

use App\Models\Meeting;
use App\Models\Misbehavior;
use League\Fractal\TransformerAbstract;

class MisbehaviorTransformer extends TransformerAbstract
{
    public function transform(Misbehavior $misbehavior): array
    {
        $data = [
            'id' => $misbehavior->id,
            'name' => $misbehavior->name,
            'behavior' => $misbehavior->behavior,
            'subject' => $misbehavior->subject,
            'grade' => $misbehavior->grade,
            'teacher' => fractal($misbehavior->teacher, new UserTransformer())->toArray(),
            'created_at' => $misbehavior->present()->createdAt,
            'updated_at' => $misbehavior->present()->updatedAt,
        ];

        return $data;
    }
}
