<?php

namespace Celcredit\Rules;

use Illuminate\Validation\Rule;

class Document
{
    public static function rules(): array
    {
        return [
            'type' => ['required', Rule::in(['NATIONAL_ID', 'OTHER'])],
            'file' => 'required|file|mimes:pdf,jpg,png|max:5120' // 5MB
        ];
    }
}