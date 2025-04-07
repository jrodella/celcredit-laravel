<?php

namespace Celcredit\Types;

use Celcredit\Types\Data;

class Pix extends Data
{
    public function __construct(
        public string $key_type,
        public string $key
    ) {}
}