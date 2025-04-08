<?php

namespace Celcredit\Types;

use Celcredit\Types\Data;

class Signature extends Data
{
    public string $ip_address;
    public string $user_agent;
    public ?string $geolocation;
    public string $signed_at;
    public Person $person;
    
    public function __construct(array $data)
    {
        parent::__construct($data);
    }
}