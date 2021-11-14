<?php

namespace App\Http\Requests;

use app\Models\School;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrUpdateSchoolRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {

        $rules = [
            'name' => ['required', 'string'],
            'address' => ['nullable', 'string'],
            'subjects' => ['nullable'],
            'lat' => ['nullable', 'numeric'],
            'lng' => ['nullable', 'numeric'],
            'radius' => ['nullable', 'numeric'],
        ];

        return $rules;
    }
}
