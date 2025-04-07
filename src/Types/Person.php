<?php

namespace Celcredit\Types;

use Celcredit\Types\Pix;
use Celcredit\Types\Data;
use Celcredit\Types\Phone;
use Celcredit\Types\Address;

class Person extends Data
{
    public function __construct(
        public string $full_name,
        public string $taxpayer_id,
        public string $sex,
        public string $nationality,
        public string $birth_date,
        public string $occupation,
        public bool $pep,
        public string $email_address,
        public Phone $phone,
        public Address $address,
        public Pix $pix
    ) {}
}