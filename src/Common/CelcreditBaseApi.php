<?php

namespace Celcredit\Common;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Celcredit\Auth\Auth;
use Celcredit\Interfaces\Attachable;

class CelcreditBaseApi
{
    public const CACHE_NAME = 'celcredit_login_token';

    public Cache $cache;

    /** @var ?string */
    public ?string $api_url;

    /** @var ?string */
    private ?string $token = null;

    // /** @var ?string */
    // private ?string $mtlsCert;

    // /** @var ?string */
    // private ?string $mtlsKey;

    // /** @var ?string */
    // private ?string $mtlsPassphrase;

    private readonly Auth $auth;

    public function __construct(?string $mtlsPassphrase = null)
    {
        $this->auth = resolve(Auth::class)->login();
        $this->api_url = config('celcredit')['api_url'];
        // $this->mtlsCert = $this->auth->getMtlsCertPath() ?? config('celcredit')['mtls_cert_path'] ?? null;
        // $this->mtlsKey = $this->auth->getMtlsKeyPath() ?? config('celcredit')['mtls_key_path'] ?? null;
        // $this->mtlsPassphrase = $this->auth->getMtlsPassphrase() ??
        //     config('celcredit')['mtls_passphrase'] ??
        //     null;
    }

    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @throws RequestException
     */
    public function getToken(): ?string
    {
        if (Cache::has($this::CACHE_NAME)) {
            $this->token = Cache::get($this::CACHE_NAME);
        } else {
            $this->token = $this->auth->getToken();
            Cache::put($this::CACHE_NAME, $this->token, 2400);
        }

        return $this->token;
    }

    // public function setPassphrase(string $passPhrase): self
    // {
    //     $this->mtlsPassphrase = $passPhrase;

    //     return $this;
    // }

    /**
     * @throws RequestException
     */
    public function get(string $endpoint, array|string|null $query = null, $responseJson = true)
    {
        $token = $this->getToken() ?? $this->auth->getToken();
        $request = Http::withToken($token)
            ->asJson()
            ->acceptJson();

        // if ($this->mtlsCert) {
        //     $request = $this->setRequestMtls($request);
        // }

        $request = $request->get($this->getFinalUrl($endpoint), $query)
            ->throw();

        return ($responseJson) ? $request->json() : $request;
    }

    /**
     * @throws RequestException
     */
    public function post(string $endpoint, array $body = [], ?Attachable $attachment = null)
    {
        $token = $this->getToken() ?? $this->auth->getToken();
        $request = Http::withToken($token)
            ->acceptJson();

        $hasAttachments = false;

        foreach ($body as $field => $document) {
            if ($document instanceof File) {
                $request->attach(
                    $field,
                    $document->getContent(),
                    $document->getFileName()
                );
                unset($body[$field]);
                $hasAttachments = true;
            }
        }

        if (!$hasAttachments) {
            $request->asJson(); // Content-Type: application/json
        }

        return $request->post($this->getFinalUrl($endpoint), $body)
            ->throw()
            ->json();
    }

    /**
     * @throws RequestException
     */
    public function put(
        string $endpoint,
        mixed $body = null,
        string $contentType = 'application/json'
    ): mixed {
        $token = $this->getToken() ?? $this->auth->getToken();
        $request = Http::withToken($token)
            ->acceptJson();

        $url = $this->getFinalUrl($endpoint);

        if (is_string($body)) {
            $request->withBody($body, $contentType);
            $data = null;
        } else {
            $request->asJson();
            $data = $body;
        }

        $response = $request->put($url, $data);

        return $response->throw()->json();
    }

    /**
     * @throws RequestException
     */
    public function patch(
        string $endpoint,
        ?array $body = null,
        bool $asJson = false
    ): mixed {
        $body_format = $asJson ? 'json' : 'form_params';
        $token = $this->getToken() ?? $this->auth->getToken();
        $request = Http::withToken($token)
            ->asJson()
            ->acceptJson()
            ->bodyFormat($body_format);

        // if ($this->mtlsCert) {
        //     $request = $this->setRequestMtls($request);
        // }

        return $request->patch($this->getFinalUrl($endpoint), $body)
            ->throw()
            ->json();
    }

    /**
     * @throws RequestException
     */
    public function delete(string $endpoint, ?array $body = null): mixed
    {
        $token = $this->getToken() ?? $this->auth->getToken();
        $request = Http::withToken($token);

        // if ($this->mtlsCert) {
        //     $request = $this->setRequestMtls($request);
        // }

        return $request->delete($this->getFinalUrl($endpoint), $body)
            ->throw()
            ->json();
    }

    // public function setRequestMtls(PendingRequest $request): PendingRequest
    // {
    //     $options = [];

    //     if ($this->mtlsCert) {
    //         $options['cert'] = $this->mtlsCert;
    //     }

    //     if ($this->mtlsKey || $this->mtlsPassphrase) {
    //         $options['ssl_key'] = [];
    //         if ($this->mtlsKey) {
    //             $options['ssl_key'][] = $this->mtlsKey;
    //         }
    //         if ($this->mtlsPassphrase) {
    //             $options['ssl_key'][] = $this->mtlsPassphrase;
    //         }
    //     }

    //     if ($options) {
    //         $request = $request->withOptions($options);
    //     }

    //     return $request;
    // }

    public function getFinalUrl(string $endpoint): string
    {
        $characters = " \t\n\r\0\x0B/";

        return rtrim($this->api_url, $characters).'/'.ltrim($endpoint, $characters);
    }
}
