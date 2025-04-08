<?php

namespace Celcredit\Rules;

use Illuminate\Validation\Rule;

class Signature
{
    public static function rules(): array
    {
        return [
            'ip_address' => ['required', 'string'],
            'person' => ['required', 'array'],
            'person.id' => 'required|uuid',
            'signed_at' => ['required', 'timestamp'],
            'user_agent' => ['required', 'string'],
            'geolocation' => ['nullable', 'string'],
        ];
    }
}