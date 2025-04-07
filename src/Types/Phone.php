<?php

namespace Celcredit\Types;

use Celcredit\Types\Data;

class Phone extends Data
{
    public string $country_code;
    public string $area_code;
    public string $number;

    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}