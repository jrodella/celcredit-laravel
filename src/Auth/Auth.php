<?php

namespace Celcredit\Auth;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Http;
use Celcredit\Events\CelcreditAuthenticatedEvent;

/**
 * Class Auth
 */
class Auth
{
    /** @var self */
    protected static $login;

    /** @var ?string */
    protected ?string $loginUrl = null;

    /** @var ?string */
    protected ?string $clientId = null;

    /** @var ?string */
    protected ?string $clientSecret = null;

    /** @var ?string */
    protected ?string $grantType = 'client_credentials';

    /** @var ?string */
    protected ?string $token = null;

    /** @var ?string */
    protected ?string $tokenExpiry = null;

    protected string $scope = 'originator';

    /**
     * Returns the instance of this class
     */
    public static function login(): self
    {
        if (is_null(self::$login)) {
            self::$login = new Auth();
        }

        self::$login->loginUrl = config('celcredit')['login_url'];

        return self::$login;
    }

    public function setClientCredentials(): self
    {
        $this->clientId ??= config('celcredit')["{$this->scope}_client_id"];
        $this->clientSecret ??= config('celcredit')["{$this->scope}_client_secret"];

        return $this;
    }

    /**
     * @param  null|string  $clientId
     * @return self
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;

        return $this;
    }

    /**
     * @param  null|string  $clientSecret
     * @return self
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;

        return $this;
    }

    /**
     * @return self
     */
    public function setGrantType(string $grantType)
    {
        $this->grantType = $grantType;

        return $this;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Reset token for new request
     */
    public function resetToken(): self
    {
        $this->token = null;

        return $this;
    }

    /**
     * @return ?string
     *
     * @throws RequestException
     */
    public function getToken(): ?string
    {
        if (now()->unix() > $this->tokenExpiry || ! $this->token) {
            $this->auth();
        }

        return $this->token;
    }

    public function setTokenExpiry(string $tokenExpiry): self
    {
        $this->tokenExpiry = $tokenExpiry;

        return $this;
    }

    public function getTokenExpiry(): mixed
    {
        return $this->tokenExpiry;
    }

    /**
     * @throws RequestException
     */
    private function auth(): void
    {
        $this->setClientCredentials();

        $body = [
            'grant_type' => $this->grantType,
            'client_secret' => $this->clientSecret,
            'client_id' => $this->clientId,
        ];

        $request = Http::asForm();
        $options = [];

        if ($options) {
            $request = $request->withOptions($options);
        }

        $response = $request->post($this->loginUrl, $body)->throw()->json();

        $this->token = $response['access_token'];
        $this->tokenExpiry = now()->addSeconds($response['expires_in'])->unix();

        event(new CelcreditAuthenticatedEvent($this->token, $this->tokenExpiry, $this->scope));
    }
}
