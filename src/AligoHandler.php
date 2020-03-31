<?php

namespace JinseokOh\Aligo;

class AligoHandler
{
    const SHORT_MESSAGE = 'SMS'; // KRW 8.4 per message
    const LONG_MESSAGE = 'LMS'; // KRW 25.9 per message
    const IMAGE_MESSAGE = 'MMS'; // KRW 60 per message

    private $client;

    /**
     * Constructor
     */
    public function __construct(AligoClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send a short text message upto 90B
     * @param string $message
     * @param array $receiverPhoneNumbers
     * @param array $receiverNames
     * @param bool $testFlag
     * @return object|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendSMS(
        string $message,
        array $receiverPhoneNumbers,
        array $receiverNames = [],
        bool $testFlag = false
    ): ?object {
        $payload = [];
        $payload['msg'] = $this->truncateMessageUpto90($message);
        $payload['msg_type'] = self::SHORT_MESSAGE;
        $payload['receiver'] = implode(',', $receiverPhoneNumbers);
        $payload['sender'] = config('aligo.phone_number');

        if (count($receiverPhoneNumbers) === count($receiverNames)) {
            $combo = [];
            foreach ($receiverPhoneNumbers as $i => $number) {
                $combo[] = "{$number}|{$receiverNames[$i]}";
            }
            $payload['destination'] = implode(',', $combo);
        }

        if ($testFlag) {
            $payload['testmode_yn'] = 'Y';
        }

        $response = $this->client->call("/send", $payload);

        return json_decode((string) $response->getBody(), false);
    }

    /**
     * Send a long text message upto 2KB
     * @param string $message
     * @param array $receiverPhoneNumbers
     * @param array $receiverNames
     * @param bool $testFlag
     * @return object|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function sendLMS(
        string $message,
        array $receiverPhoneNumbers,
        array $receiverNames = [],
        bool $testFlag = false
    ): ?object {
        $payload = [];
        $payload['msg'] = $message;
        $payload['msg_type'] = self::LONG_MESSAGE;
        $payload['receiver'] = implode(',', $receiverPhoneNumbers);
        $payload['sender'] = config('aligo.phone_number');

        if (count($receiverPhoneNumbers) === count($receiverNames)) {
            $combo = [];
            foreach ($receiverPhoneNumbers as $i => $number) {
                $combo[] = "{$number}|{$receiverNames[$i]}";
            }
            $payload['destination'] = implode(',', $combo);
        }

        if ($testFlag) {
            $payload['testmode_yn'] = 'Y';
        }

        $response = $this->client->call("/send", $payload);

        return json_decode((string) $response->getBody(), false);
    }

    /**
     * request the remaining credits
     * @return object|null
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function remain(): ?object {
        $response = $this->client->call("/remain");

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
}
