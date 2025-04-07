<?php

namespace Celcredit\Types;

use Celcredit\Types\Pix;
use Celcredit\Types\Data;
use Celcredit\Types\Phone;
use Celcredit\Types\Address;

class Business extends Data
{
    public string $legal_name;
    public string $taxpayer_id;
    public string $foudation_date;
    public string $email_address;
    public Phone $phone;
    public Address $address;
    public Pix $pix;

    public function __construct(array $data)
    {
        $data['address'] = new Address($data['address'] ?? []);
        $data['phone'] = new Phone($data['phone'] ?? []);
        $data['pix'] = new Pix($data['pix'] ?? []);
        parent::__construct($data);
    }
}