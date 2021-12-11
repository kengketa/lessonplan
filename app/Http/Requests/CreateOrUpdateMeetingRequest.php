<?php

namespace App\Http\Requests;

use app\Models\Meeting;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrUpdateMeetingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules=[

            'school_id'	=>	['required', ],
			'title'	=>	['required', 'string', ],
			'date'	=>	['nullable', 'date', ],
			'status'	=>	['nullable', ],
			
        ];

        return $rules;
    }
}
