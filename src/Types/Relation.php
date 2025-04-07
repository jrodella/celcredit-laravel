<?php

namespace Celcredit\Types;

use Celcredit\Types\Data;

class Relation extends Data
{
    public string $type;
    public Person $person;
    public bool $signer;

    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}