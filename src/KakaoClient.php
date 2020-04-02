<?php

namespace JinseokOh\Aligo;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Cache;

class KakaoClient implements AligoClientContract
{
    const ACCESS_TOKEN_KEY = 'aligo-access-token';

    private $client;
    private $appId;
    private $appKey;
    private $senderPhoneNumber;
    private $kakaoKey;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->client = new GuzzleClient([
            'base_uri' => config('aligo.kakao_url'),
        ]);
        $this->appId = config('aligo.app_id');
        $this->appKey = config('aligo.app_key');
        $this->senderPhoneNumber = config('aligo.phone_number');
        $this->kakaoKey = config('aligo.kakao_sender_key');
    }

    /**
     * Send a KakaoTalk message
     *
     * @param string $message
     * @param string $receiverPhoneNumber
     * @param bool $testFlag
     * @return object|null
     * @throws \GuzzleHttp\Exception\GuzzleException|\Exception
     */
    public function sendMessage(
        string $message,
        string $receiverPhoneNumber,
        bool $testFlag = false
    ): ?object {
        $payload = [];
        $payload['senderkey'] = $this->kakaoKey;
        $payload['sender'] = $this->senderPhoneNumber;
        $payload['token'] = $this->getAccessToken($testFlag);
        $payload['message_1'] = $message;
        $payload['receiver_1'] = $receiverPhoneNumber;
        $template = $this->getMatchingTemplate($message);
        $payload['tpl_code'] = $template['code'];
        $payload['subject_1'] = $template['subject'];
        if ($template['button']) {
            $payload['button_1'] = $template['button'];
        }

        $response = $this->call("/akv10/alimtalk/send/", $payload);

        return json_decode((string) $response->getBody(), false);
    }

    // ================================================================================
    // private methods
    // ================================================================================

    /**
     * @param string $message
     * @return array
     * @throws \Exception
     */
    public function getMatchingTemplate(string $message): array
    {
        $templates = config('aligo.kakao_templates');

        foreach ($templates as $template) {
            $regex = preg_replace(
                ["/\#\{[^\}]+\}/", "/\./", "/\!/", "/\?/",],
                ["\w+", "\.", "\!", "\?",],
                $template['message']
            );
            if (preg_match("/{$regex}/", $message)) {
                return $template;
            }
        }

        throw new \Exception('Holy cow! No matching template is found.');
    }

    /**
     * @return string
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAccessToken($testFlag = false): string
    {
        // Cache::forget(self::ACCESS_TOKEN_KEY);
        return Cache::remember(self::ACCESS_TOKEN_KEY, 25 * 60, function () use ($testFlag) {
            $payload = $testFlag ? [ 'testMode' => 'Y' ] : [];

            $response = $this->call('/akv10/token/create/30/i', $payload);

            $data = json_decode((string) $response->getBody(), false);

            return $data->urlencode;
        });
    }

    /**
     *
     * @param string $uri
     * @param array $body
     * @param string $method
     * @return mixed|\Psr\Http\Message\ResponseInterface
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function call(string $uri, array $body = [], string $method = 'POST')
    {
        return $this->client->request($method, $uri, [
            'form_params' => array_merge([
                'userid' => $this->appId,
                'apikey' => $this->appKey,
            ], $body)
        ]);
    }
}
