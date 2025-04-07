<?php

namespace Celcredit\Rules;

class Phone
{
    public static function rules(): array
    {
        return [
            'country_code' => 'required|string|max:2',
            'area_code' => 'required|string|between:2,3',
            'number' => 'required|string|min:8|max:9'
        ];
    }
}