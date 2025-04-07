<?php

namespace Celcredit\Rules;

class Address
{
    public static function rules(): array
    {
        return [
            'street_number' => 'required|integer|min:1',
            'street_name' => 'required|string|max:255',
            'postal_code' => 'required|numeric|digits:8',
            'district' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'state_code' => 'required|string|size:2',
            'country_code' => 'required|string|size:3'
        ];
    }
}