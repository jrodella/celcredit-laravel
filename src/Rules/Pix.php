<?php

namespace Celcredit\Rules;

use Illuminate\Validation\Rule;

class Pix
{
    public static function rules(): array
    {
        return [
            'key_type' => ['required', Rule::in(['TAXPAYER_ID', 'PHONE', 'EMAIL', 'RANDOM_KEY'])],
            'key' => 'required|string'
        ];
    }
}