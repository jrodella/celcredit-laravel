<?php

namespace Celcredit\Rules;

use Illuminate\Validation\Rule;

class Business
{
    public static function rules(): array
    {
        return [
            'legal_name' => 'required|string|max:150',
            'taxpayer_id' => 'required|max_digits:14',
            'foudation_date' => 'required|date_format:Y-m-d',
            'email_address' => 'required|email|max:100'
        ];
    }
}