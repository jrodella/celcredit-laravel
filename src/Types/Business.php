<?php

namespace Celcredit\Types;

use Celcredit\Types\Pix;
use Celcredit\Types\Data;
use Celcredit\Types\Phone;
use Celcredit\Types\Address;

class Business extends Data
{
    public function __construct(
        public string $legal_name,
        public string $taxpayer_id,
        public string $foudation_date,
        public string $email_address,
        public Phone $phone,
        public Address $address,
        public Pix $pix
    ) {}
}