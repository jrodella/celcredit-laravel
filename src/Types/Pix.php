<?php

namespace Celcredit\Types;

use Celcredit\Types\Data;

class Pix extends Data
{
    public string $key_type;
    public string $key;

    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}