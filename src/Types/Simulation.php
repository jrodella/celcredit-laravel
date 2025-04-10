<?php

namespace Celcredit\Types;

use Celcredit\Types\Data;

class Simulation extends Data
{
    public float $total_amount_owed;
    public float $requested_amount;
    public float $interest_rate;
    public float $tac_amount;
    public float $finance_fee;
    public string $iofType;
    public int $num_payments;
    public string $first_payment_date;
    public string $disbursement_date;
    public string $borrower_type;
    public string $schedule_type;

    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}