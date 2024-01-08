<?php

namespace App\Http\Requests;

use app\Models\Subject;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrUpdateSubjectRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules=[

            'code'	=>	['required', 'string', ],
			'name'	=>	['required', 'string', ],
			'unit'	=>	['required', ],
			
        ];

        return $rules;
    }
}
