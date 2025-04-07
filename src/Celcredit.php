<?php

namespace Celcredit;

use Celcredit\Clients\BankingOriginatorClient;

/**
 * Class Celcredit
 */
class Celcredit
{
    public static function bankingOriginator(): BankingOriginatorClient
    {
        return new BankingOriginatorClient();
    }
}
