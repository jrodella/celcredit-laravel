<?php

namespace Celcredit\Rules;

use Illuminate\Validation\Rule;

class Application
{
    public static function rules(): array
    {
        return [
            'product.id' => 'required|uuid',
            'borrower.id' => 'required|uuid',
            'funding.id' => 'required|uuid',
            'payment_method' => ['required', Rule::in(['PIX'])],
            'signature_collect_method' => ['required', Rule::in(['LINK', 'NONE'])],
            'num_payments' => 'required|integer|min:1',
        ];
    }
}