<?php

namespace Celcredit\Rules;

use Illuminate\Validation\Rule;

class Relation
{
    public static function rules(): array
    {
        return [
            'type' => ['required', Rule::in(['EMPLOYEE'])],
            'person.id' => 'required|uuid',
            'signer' => 'required|boolean',
        ];
    }
}