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
        $rules = [
            'file' => ['required', 'mimes:xlsx,xls', 'max:10240']
        ];
        return $rules;
    }
}
