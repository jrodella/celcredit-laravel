<?php

namespace Celcredit\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @uses Celcredit::bankingOriginator
 */
class CelcreditFacade extends Facade
{
    /**
     * Get the registered name of the component.
     */
    protected static function getFacadeAccessor(): string
    {
        return 'celcredit';
    }
}
