<?php

namespace App\Transformers;

use App\Models\Agenda;
use League\Fractal\TransformerAbstract;

class AgendaTransformer extends TransformerAbstract
{
    public function transform(Agenda $agenda): array
    {
        $data = [
            'id'	=>	$agenda->id,
			'meeting_id'	=>	$agenda->meeting_id,
			'topic'	=>	$agenda->topic,
			'detail'	=>	$agenda->detail,
			'decision'	=>	$agenda->decision,
			'created_at'	=>	$agenda->present()->createdAt,
			'updated_at'	=>	$agenda->present()->updatedAt,
        ];

        return $data;
    }
}
