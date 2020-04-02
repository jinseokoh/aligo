<?php

namespace JinseokOh\Aligo;

use GuzzleHttp\Client as GuzzleClient;

class AligoTextClient
{
    private $client;
    private $appId;
    private $appKey;
    private $smsFrom;

    /**
     * AligoTextClient constructor.
     * @param string $appId
     * @param string $appKey
     * @param string $smsFrom
     */
    public function __construct(string $appId, string $appKey, string $smsFrom)
    {
        $this->client = new GuzzleClient([
            'base_uri' => 'https://apis.aligo.in'
        ]);
        $this->appId = $appId;
        $this->appKey = $appKey;
        $this->smsFrom = $smsFrom;
    }

    /**
     * @param AligoTextMessage $message
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(AligoTextMessage $message)
    {
        $payload = [
            'msg' => $message->content,
            'msg_type' => $message->type,
            'receiver' => $message->to,
            'sender' => $this->smsFrom,
        ];
        if ($message->debug) {
            $payload = array_merge([
                'testmode_yn' => 'Y'
            ], $payload);
        }
        $response = $this->call("/send", $payload);

        return json_decode((string) $response->getBody(), false);
    }

    /**
     * @param string $uri
     * @param array $body
     * @param string $method
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function call(string $uri, array $body = [], string $method = 'POST')
    {
        return $this->client->request($method, $uri, [
            'headers' => [
                'Content-Type' => 'application/json; charset=UTF-8',
                'Accept' => 'application/json',
            ],
            'query' => array_merge([
                'user_id' => $this->appId,
                'key' => $this->appKey,
            ], $body)
        ]);
    }
}
