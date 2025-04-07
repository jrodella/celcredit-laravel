<?php

namespace Celcredit\Rules;

use Illuminate\Validation\Rule;

class Simulation
{
    public static function rules(): array
    {
        return [
            'requested_amount' => 'required|numeric|min:0',
            'interest_rate' => 'required|numeric|between:0,1',
            'first_payment_date' => 'required|date_format:Y-m-d',
            'disbursement_date' => 'required|date_format:Y-m-d|after:today',
            'borrower_type' => ['required', Rule::in(['PERSON'])],
            'schedule_type' => ['required', Rule::in(['MONTHLY'])],
        ];
    }
}