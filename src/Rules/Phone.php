<?php

namespace Celcredit\Rules;

class Phone
{
    public static function rules(): array
    {
        return [
            'country_code' => 'required|string',
            'area_code' => 'required|string',
            'number' => 'required|string'
        ];
    }
}