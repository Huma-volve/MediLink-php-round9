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
            'booking_type' => [
                'required',
                Rule::in(['myself', 'other'])
            ],

            'patient_name' => [
                'required_if:booking_type,other',
                'string',
                'min:2',
                'max:100',
            ],
            'patient_phone' => [
                'required_if:booking_type,other',
                'string'
            ],

            'patient_email' => [
                'required_if:booking_type,other',
                'string',
                'email',
                'max:255',
            ],

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
