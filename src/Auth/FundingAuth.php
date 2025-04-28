<?php

namespace Celcredit\Auth;

class FundingAuth extends Auth
{
    /** @var self */
    protected static $login;

    /** @var string */
    protected string $scope = 'funding';

    /**
     * Returns the instance of this class
     */
    public static function login(): self
    {
        if (is_null(self::$login)) {
            self::$login = new self();
        }
        self::$login->loginUrl = config('celcredit')['login_url'];

        return self::$login;
    }
}
