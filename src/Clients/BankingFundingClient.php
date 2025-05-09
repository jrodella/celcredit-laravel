<?php

namespace Celcredit\Clients;

use Celcredit\Common\CelcreditBaseApi;

class BankingFundingClient extends CelcreditBaseApi
{
    // Rotas definidas como constantes
    public const ACCOUNT_BALANCE = '/banking/funding/account/balance';

    public function __construct(string $fundingAccount = 'default')
    {
        parent::__construct($fundingAccount);
    }

    public function getAccountBalance(): array
    {
        $response = $this->get(self::ACCOUNT_BALANCE);

        return $response;
    }
}
