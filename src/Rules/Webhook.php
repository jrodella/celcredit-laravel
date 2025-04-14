<?php

namespace Celcredit\Rules;

use Illuminate\Validation\Rule;

class Webhook
{
    public static function rules()
    {
        return [
            'url' => ['required', 'string', 'url'],
        ];
    }
}
