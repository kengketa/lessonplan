<?php

namespace App\Http\Requests;

use app\Models\Meeting;
use Illuminate\Foundation\Http\FormRequest;

class CreateOrUpdateMeetingRequest extends FormRequest
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
            'title' => ['required', 'string'],
            'date' => ['nullable', 'date'],
            'time' => ['nullable', 'date_format:H.i'],
            'location' => ['nullable'],
            'attendee' => ['nullable'],
            'agendas' => ['required'],
            'agendas.*.id' => ['nullable'],
            'agendas.*.topic' => ['required'],
            'agendas.*.detail' => ['nullable'],
            'agendas.*.decision' => ['nullable'],
        ];
        return $rules;
    }
}
