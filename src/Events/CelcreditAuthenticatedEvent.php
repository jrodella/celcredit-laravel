<?php

namespace Celcredit\Events;

class CelcreditAuthenticatedEvent
{
    public string $token;

    public string $tokenExpiry;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $token, string $tokenExpiry)
    {
        $this->token = $token;
        $this->tokenExpiry = $tokenExpiry;
    }
}
