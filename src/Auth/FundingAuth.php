<?php

namespace Celcredit\Auth;

class FundingAuth extends Auth
{
    protected string $scope = 'funding';
    protected string $accountName;
    protected string $accountIdentifier;

    public function __construct(string $accountName = 'default')
    {
        if (!array_key_exists($accountName, config('celcredit.funding'))) {
            throw new \InvalidArgumentException("Conta funding '{$accountName}' não configurada!");
        }
        
        $this->accountName = $accountName;
    }

    public function setClientCredentials(): self
    {
        $config = config("celcredit.funding.{$this->accountName}");
        
        if (empty($config['client_id']) || empty($config['client_secret'])) {
            throw new \InvalidArgumentException("Credenciais para a conta '{$this->accountName}' não encontradas!");
        }

        $this->clientId = $config['client_id'];
        $this->clientSecret = $config['client_secret'];

        return $this;
    }

    public static function forAccount(?string $accountName = null): self
    {
        $instance = new self($accountName ?? 'default');
        $instance->loginUrl = config('celcredit.login_url');
        return $instance;
    }

    public function getAccountIdentifier(): string
    {
        return $this->accountIdentifier;
    }
}