<?php

namespace Celcredit\Types;

use Celcredit\Types\Data;

class Address extends Data
{
    public int $street_number;
    public string $street_name;
    public int $postal_code;
    public string $district;
    public string $city;
    public string $state_code;
    public string $country_code;

    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}