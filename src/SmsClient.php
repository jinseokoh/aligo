<?php

namespace JinseokOh\Aligo;

use GuzzleHttp\Client as GuzzleClient;

class SmsClient implements AligoClientContract
{
    const SHORT_MESSAGE = 'SMS'; // KRW 8.4 per message
    const LONG_MESSAGE = 'LMS'; // KRW 25.9 per message

    private $client;
    private $appId;
    private $appKey;
    private $senderPhoneNumber;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->client = new GuzzleClient([
            'base_uri' => config('aligo.sms_url'),
        ]);
        $this->appId = config('aligo.app_id');
        $this->appKey = config('aligo.app_key');
        $this->senderPhoneNumber = config('aligo.phone_number');
    }

    /**
     * @param string $message
     * @param string $receiverPhoneNumber
     * @param bool $testFlag
     * @return object|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendMessage(
        string $message,
        string $receiverPhoneNumber,
        bool $testFlag = false
    ): ?object {
        $payload = [];
        $payload['msg'] = $this->truncateMessageUpto90($message);
        $payload['msg_type'] = self::SHORT_MESSAGE;
        $payload['receiver'] = $receiverPhoneNumber;
        $payload['sender'] = $this->senderPhoneNumber;
        if ($testFlag) {
            $payload['testmode_yn'] = 'Y';
        }
        $response = $this->call("/send", $payload);

        return json_decode((string) $response->getBody(), false);
    }

    /**
     * Send a long text message upto 2KB
     * @param string $message
     * @param array $receiverPhoneNumbers
     * @param bool $testFlag
     * @return object|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendLongMessage(
        string $message,
        array $receiverPhoneNumbers,
        bool $testFlag = false
    ): ?object {
        $payload = [];
        $payload['msg'] = $message;
        $payload['msg_type'] = self::LONG_MESSAGE;
        $payload['receiver'] = implode(',', $receiverPhoneNumbers);
        $payload['sender'] = $this->senderPhoneNumber;
        if ($testFlag) {
            $payload['testmode_yn'] = 'Y';
        }

        $response = $this->call("/send", $payload);

        return json_decode((string) $response->getBody(), false);
    }

    // ================================================================================
    // private methods
    // ================================================================================

    /**
     * @param string $message
     * @return int
     */
    private function calcMessageSize(string $message): int
    {
        $koreanOnly = preg_replace("/[[:alnum:]]|[[:space:]]|[[:punct:]]/", "", $message);

        return mb_strlen($message) + mb_strlen($koreanOnly);
    }

    /**
     * @param string $message
     * @return string
     */
    private function truncateMessageUpto90(string $message): string
    {
        while ($this->calcMessageSize($message) > 90)	{
            $message = mb_substr($message, 0, -1);
        }

        return $message;
    }

    /**
     * @param string $uri
     * @param array $body
     * @param string $method
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
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
