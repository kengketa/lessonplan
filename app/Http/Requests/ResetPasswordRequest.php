<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ResetPasswordRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // base rules
        $rules = [
            "password" => ["required", "string", "min:8", "max:255", "confirmed"],
            "user_id" => ["required", "exists:user_setups,user_id"],
            "token" => ["required", "string", "exists:user_setups,token"],
        ];

        return $rules;
    }
}
