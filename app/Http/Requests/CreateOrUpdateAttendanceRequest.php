<?php

namespace App\Http\Requests;

use app\Models\Attendance;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrUpdateAttendanceRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'student_id' => ['required', 'exists:students,id'],
            'present' => ['required', 'boolean']
        ];

        return $rules;
    }
}
