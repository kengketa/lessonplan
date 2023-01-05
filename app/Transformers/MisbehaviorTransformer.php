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
            'teacher' => fractal($misbehavior->teacher, new UserTransformer())->toArray()
        ];

        return $data;
    }
}
