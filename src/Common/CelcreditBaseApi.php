<?php

namespace Celcredit\Common;

use Illuminate\Http\Client\RequestException;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Celcredit\Auth\Auth;
use Celcredit\Auth\FundingAuth;

class CelcreditBaseApi
{
    public const CACHE_NAME = 'celcredit_login_token';
    public const CACHE_NAME_FUNDING = 'celcredit_login_funding_token';

    public Cache $cache;

    /** @var ?string */
    public ?string $api_url;

    /** @var ?string */
    private ?string $token = null;

    /** @var ?string */
    private ?string $funding_token = null;

    protected readonly Auth $auth;

    protected readonly FundingAuth $funding_auth;

    public function __construct(string $fundingAccount = 'default')
    {
        $this->auth = resolve(Auth::class)->login();
        $this->funding_auth = resolve(FundingAuth::class)->forAccount($fundingAccount);
        $this->api_url = config('celcredit')['api_url'];
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

    public function setFundingToken(string $token): void
    {
        $this->funding_token = $token;
    }

    /**
     * @throws RequestException
     */
    public function getFundingToken(): ?string
    {
        $cacheKey = self::CACHE_NAME_FUNDING . "_" . $this->funding_auth->getAccountIdentifier();
    
        if (Cache::has($cacheKey)) {
            $this->funding_token = Cache::get($cacheKey);
        } else {
            $this->funding_token = $this->funding_auth->getToken();
            Cache::put($cacheKey, $this->funding_token, 2400);
        }
    
        return $this->funding_token;
    }

    public function tokenResolver(string $endpoint): string
    {
        if (str_contains($endpoint, 'funding')) {
            return $this->getFundingToken();
        }
    
        return $this->getToken();
    }

    /**
     * @throws RequestException
     */
    public function get(string $endpoint, array|string|null $query = null, $responseJson = true, array|null $options = null)
    {
        $token = $this->tokenResolver($endpoint);
        $request = Http::withToken($token)
            ->asJson()
            ->acceptJson();

        if ($options) {
            $request = $request->withOptions($options);
        }

        $request = $request->get($this->getFinalUrl($endpoint), $query)
            ->throw();

        return ($responseJson) ? $request->json() : $request;
    }

    /**
     * @throws RequestException
     */
    public function post(string $endpoint, array $body = []): array
    {
        $token = $this->tokenResolver($endpoint);
        $request = Http::withToken($token)->acceptJson();

        $hasAttachments = false;
        $isGuzzleMultipart = !empty($body) && isset($body[0]['name']);

        if ($isGuzzleMultipart) {
            return $request->asMultipart()
                ->post($this->getFinalUrl($endpoint), $body)
                ->throw()
                ->json();
        }

        // Original logic for auto-switching between JSON/multipart
        foreach ($body as $field => $value) {
            if ($value instanceof File) {
                $multipart[] = [
                    'name' => $field,
                    'contents' => $value->getContent(),
                    'filename' => $value->getFileName()
                ];
                unset($body[$field]);
                $hasAttachments = true;
            } else {
                $multipart[] = ['name' => $field, 'contents' => $value];
            }
        }

        if (!$hasAttachments) {
            $request->asJson(); // Content-Type: application/json
        } else {
            return $request->asMultipart()
                ->post($this->getFinalUrl($endpoint), $multipart)
                ->throw()
                ->json();
        }

        return $request->asJson()
            ->post($this->getFinalUrl($endpoint), $body)
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
        $token = $this->tokenResolver($endpoint);
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
        $token = $this->tokenResolver($endpoint);
        $request = Http::withToken($token)
            ->asJson()
            ->acceptJson()
            ->bodyFormat($body_format);

        return $request->patch($this->getFinalUrl($endpoint), $body)
            ->throw()
            ->json();
    }

    /**
     * @throws RequestException
     */
    public function delete(string $endpoint, ?array $body = null): mixed
    {
        $token = $this->tokenResolver($endpoint);
        $request = Http::withToken($token);

        return $request->delete($this->getFinalUrl($endpoint), $body)
            ->throw()
            ->json();
    }

    public function getFinalUrl(string $endpoint): string
    {
        $characters = " \t\n\r\0\x0B/";

        return rtrim($this->api_url, $characters).'/'.ltrim($endpoint, $characters);
    }
}
