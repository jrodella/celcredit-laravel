<?php

namespace Celcredit\Rules;

class Address
{
    public static function rules(): array
    {
        return [
            'street_number' => 'required|numeric',
            'street_name' => 'required|string',
            'postal_code' => 'required|numeric|digits:8',
            'district' => 'required|string',
            'city' => 'required|string',
            'state_code' => 'required|string',
            'country_code' => 'required|string'
        ];
    }
}