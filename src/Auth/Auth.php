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

    // /**
    //  * @var ?string
    //  */
    // protected ?string $mtlsPassphrase = null;

    // protected ?string $mtlsCert = null;

    // protected ?string $mtlsKey = null;

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
        $this->clientId = $this->clientId ?? config('celcredit')['originator_client_id'];
        $this->clientSecret = $this->clientSecret ?? config('celcredit')['originator_client_secret'];
        // $this->mtlsPassphrase = $this->mtlsPassphrase ?? config('celcredit.mtls_passphrase');
        // $this->mtlsCert = $this->mtlsCert ?? config('celcredit.mtls_cert_path') ?? null;
        // $this->mtlsKey = $this->mtlsCert ?? config('celcredit.mtls_key_path') ?? null;

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

    // /**
    //  * @return $this
    //  */
    // public function setPassphrase(string $passPhrase): self
    // {
    //     $this->mtlsPassphrase = $passPhrase;

    //     return $this;
    // }

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

    // public function setCertPath(string $path): self
    // {
    //     $this->mtlsCert = $path;

    //     return $this;
    // }

    // /**
    //  * Set the cert.pem file path
    //  */
    // public function setKeyPath(string $path): self
    // {
    //     $this->mtlsKey = $path;

    //     return $this;
    // }

    // public function getMtlsKeyPath(): ?string
    // {
    //     return $this->mtlsKey;
    // }

    // public function getMtlsPassphrase(): ?string
    // {
    //     return $this->mtlsPassphrase;
    // }

    // public function getMtlsCertPath(): ?string
    // {
    //     return $this->mtlsCert;
    // }

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

        // if ($this->mtlsCert) {
        //     $options['cert'] = $this->mtlsCert;
        // }

        // if ($this->mtlsKey || $this->mtlsPassphrase) {
        //     $options['ssl_key'] = [];
        //     if ($this->mtlsKey) {
        //         $options['ssl_key'][] = $this->mtlsKey;
        //     }
        //     if ($this->mtlsPassphrase) {
        //         $options['ssl_key'][] = $this->mtlsPassphrase;
        //     }
        // }

        if ($options) {
            $request = $request->withOptions($options);
        }

        $response = $request->post($this->loginUrl, $body)->throw()->json();

        $this->token = $response['access_token'];
        $this->tokenExpiry = now()->addSeconds($response['expires_in'])->unix();

        event(new CelcreditAuthenticatedEvent($this->token, $this->tokenExpiry));
    }
}
