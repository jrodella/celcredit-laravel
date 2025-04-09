<?php

namespace Celcredit\Types;

use Celcredit\Types\Data;

class Signature extends Data
{
    public string $ip_address;
    public string $user_agent;
    public ?string $geolocation;
    public string $signed_at;
    public array $person;

    public function __construct(array $data)
    {
        $data['person'] = $data['person'] ?? [];
        parent::__construct($data);
    }
}