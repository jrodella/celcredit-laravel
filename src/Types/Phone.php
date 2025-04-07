<?php

namespace Celcredit\Types;

use Celcredit\Types\Data;

class Phone extends Data
{
    public function __construct(
        public string $country_code,
        public string $area_code,
        public string $number
    ) {}
}