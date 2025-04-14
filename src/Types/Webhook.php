<?php

namespace Celcredit\Types;

use Celcredit\Types\Data;

class Webhook extends Data
{
    public string $url;

    public function __construct(array $data = [])
    {
        parent::__construct($data);
    }
}
