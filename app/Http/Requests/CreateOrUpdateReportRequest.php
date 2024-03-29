<?php

namespace App\Http\Requests;

use app\Models\Report;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class CreateOrUpdateReportRequest extends FormRequest
{

    public function messages()
    {
        return [
//            'report.for_grades.*.id.required' => 'class is required.',
        ];
    }

    public function rules()
    {
        $rules = [
            'school_id' => ['required', 'exists:schools,id'],
            'subject' => ['required', 'numeric'],
            'week_number' => ['required', 'numeric'],
            'lesson_number' => ['required', 'numeric'],
            'teaching_materials' => ['nullable'],
            'activities' => ['nullable'],
            'outcome' => ['nullable'],
            'outstanding_students' => ['nullable'],
            'need_improvement_students' => ['nullable'],
            'misbehavior_students' => ['nullable'],
            'misbehavior_students.*.name' => ['required'],
            'misbehavior_students.*.behavior' => ['required'],
            'report.for_grades' => ['required'],
            'report.for_grades.*.id' => ['required', 'exists:grades,id'],
            'report.for_grades.*.date' => ['nullable', 'date'],

            'report.plans' => ['required'],
            'report.plans.*.type' => ['required'],
            'report.plans.*.topic' => ['required'],
            'report.plans.*.vocabs' => ['nullable'],
            'report.plans.*.details' => ['nullable'],
        ];
        return $rules;
    }
}
