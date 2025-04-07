<?php

namespace Celcredit\Types;

use Celcredit\Types\Data;

class Relation extends Data
{
    public function __construct(
        public string $type,
        public Person $person,
        public bool $signer
    ) {}
}