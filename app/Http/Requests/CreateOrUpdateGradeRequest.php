<?php

namespace App\Http\Requests;

use app\Models\Grade;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrUpdateGradeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $rules = [
            'school_id' => ['required'],
            'type' => ['required', 'numeric', 'in:1,2,3,4'],
            'level' => ['required', 'numeric'],
            'room_number' => ['required', 'numeric'],
        ];
        return $rules;
    }
}
