<?php

namespace App\Http\Requests;

use app\Models\Report;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrUpdateReportRequest extends FormRequest
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
			'week_number'	=>	['required', ],
			'lesson_number'	=>	['required', ],
			'date'	=>	['nullable', 'date', ],
			'topic'	=>	['nullable', 'json', ],
			'subject'	=>	['nullable', 'string', ],
			'outcome'	=>	['nullable', 'string', ],
			'outstanding_student'	=>	['nullable', 'string', ],
			'need_improvement_student'	=>	['nullable', 'string', ],
			'creator'	=>	['required', ],
			'approver'	=>	['nullable', ],
			
        ];

        return $rules;
    }
}
