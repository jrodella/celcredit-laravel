<?php

namespace Celcredit\Types;

use Celcredit\Types\Data;

class Application extends Data
{
    public array $product;
    public array $borrower;
    public array $funding;
    public array $beneficiary_account;
    public float $requested_amount;
    public float $interest_rate;
    public float $tac_amount;
    public float $finance_fee;
    public int $num_payments;
    public string $first_payment_date;
    public string $disbursement_date;
    public string $payment_method;
    public string $signature_collect_method;

    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}