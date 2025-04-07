<?php

namespace Celcredit\Rules;

use Illuminate\Validation\Rule;

class Person
{
    public static function rules(): array
    {
        return [
            'full_name' => 'required|string|max:150',
            'taxpayer_id' => 'required|cpf',
            'sex' => ['required', Rule::in(['MALE', 'FEMALE'])],
            'nationality' => 'required|string|max:50',
            'birth_date' => 'required|date_format:Y-m-d',
            'occupation' => 'required|string|max:100',
            'pep' => 'required|boolean',
            'email_address' => 'required|email|max:100'
        ];
    }
}