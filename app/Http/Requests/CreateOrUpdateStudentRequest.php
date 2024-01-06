<?php

namespace App\Http\Requests;

use app\Models\Student;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrUpdateStudentRequest extends FormRequest
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
			'prefix'	=>	['required', 'string', ],
			'first_name'	=>	['required', 'string', ],
			'last_name'	=>	['required', 'string', ],
			'email'	=>	['nullable', 'string', ],
			'password'	=>	['nullable', 'string', ],
			'phone'	=>	['nullable', 'string', ],
			
        ];

        return $rules;
    }
}
