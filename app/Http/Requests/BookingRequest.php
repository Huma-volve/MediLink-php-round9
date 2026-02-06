<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {

        return [

            'appointment_date' => [
                'required',
                'date',
            ],

            'appointment_time' => [
                'required',
                'date_format:H:i'
            ],

            'reason_for_visit' => [
                'nullable',
                'string',
                'max:1000'
            ],

            'consultation_type' => [
                'required',
                Rule::in(['in_person', 'online'])
            ],
        ];
    }
}
