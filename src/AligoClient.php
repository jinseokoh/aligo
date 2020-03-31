<?php

namespace JinseokOh\Aligo;

use GuzzleHttp\Client as GuzzleClient;

class AligoClient
{
    private $client;
    private $appId;
    private $appKey;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->client = new GuzzleClient([
            'base_uri' => config('aligo.base_url'),
        ]);
        $this->appId = config('aligo.app_id');
        $this->appKey = config('aligo.app_key');
    }

    /**
     * @return string
     */
    public function getAppId(): string
    {
        return $this->appId;
    }

    /**
     * @return string
     */
    public function getAppKey(): string
    {
        return $this->appKey;
    }

    /**
     * @param string $uri
     * @param array $body
     * @param string $method
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function call(string $uri, array $body = [], string $method = 'POST')
    {
        return $this->client->request($method, $uri, [
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8',
                'Accept' => 'application/json',
            ],
            'query' => $this->getBody($body),
        ]);
    }

    // ================================================================================
    // private methods
    // ================================================================================

    /**
     * @param array $body
     * @return array
     */
    private function getBody(array $body = []): array
    {
        return array_merge([
            'key' => $this->getAppKey(),
            'user_id' => $this->getAppId(),
        ], $body);
    }
}
