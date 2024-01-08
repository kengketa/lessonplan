<?php

namespace App\Http\Requests;

use app\Models\Enrollment;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrUpdateEnrollmentRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules=[

            'grade_id'	=>	['required', ],
			'academic_year'	=>	['required', ],
			'student_id'	=>	['required', ],
			'semester'	=>	['required', ],
			
        ];

        return $rules;
    }
}
