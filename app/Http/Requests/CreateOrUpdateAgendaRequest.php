<?php

namespace App\Http\Requests;

use app\Models\Agenda;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrUpdateAgendaRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules=[

            'meeting_id'	=>	['required', ],
			'topic'	=>	['required', 'string', ],
			'detail'	=>	['nullable', 'string', ],
			'decision'	=>	['nullable', 'string', ],
			
        ];

        return $rules;
    }
}
