<?php

namespace Celcredit\Rules;

use Illuminate\Validation\Rule;

class Person
{
    public static function rules(): array
    {
        return [
            'full_name' => 'required|string',
            'taxpayer_id' => 'required|max_digits:11',
            'sex' => ['required', Rule::in(['MALE', 'FEMALE', 'OTHER'])],
            'nationality' => 'required|string',
            'birth_date' => 'required|date_format:Y-m-d',
            'occupation' => 'required|string',
            'pep' => 'required|boolean',
            'email_address' => 'required|email',
            'mother_name' => 'nullable|string',
            'spouse_full_name' => 'nullable|string'
        ];
    }
}