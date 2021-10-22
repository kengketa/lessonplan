<?php

namespace App\Http\Requests;

use app\Models\Report;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CreateOrUpdateReportRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'school_id' => ['required', 'exists:schools,id'],
            'subject' => ['required', 'numeric'],
            'week_number' => ['required', 'numeric'],
            'lesson_number' => ['required', 'numeric'],
            'report.for_grades' => ['required'],
            'report.for_grades.*.id' => ['required', 'exists:grades,id'],
            'report.for_grades.*.date' => ['required', 'date'],

            'report.plans' => ['required'],
            'report.plans.*.type' => ['required'],
            'report.plans.*.topic' => ['required'],
            'report.plans.*.vocabs' => ['nullable'],
            'report.plans.*.details' => ['nullable'],
        ];
        return $rules;
    }
}
