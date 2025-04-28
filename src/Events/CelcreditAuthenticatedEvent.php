<?php

namespace Celcredit\Events;

class CelcreditAuthenticatedEvent
{
    public string $token;

    public string $tokenExpiry;

    public string $scope;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(string $token, string $tokenExpiry, string $scope)
    {
        $this->token = $token;
        $this->tokenExpiry = $tokenExpiry;
        $this->scope = $scope;
    }
}
