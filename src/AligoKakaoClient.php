<?php

namespace JinseokOh\Aligo;

use GuzzleHttp\Client as GuzzleClient;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use JinseokOh\Aligo\Dtos\KakaoTemplateButtonDto;
use JinseokOh\Aligo\Dtos\KakaoTemplateDto;
use JinseokOh\Aligo\Exceptions\CouldNotSendNotification;

class AligoKakaoClient
{
    const ALIGO_ACCESS_TOKEN_FILE = 'aligo-access-token.conf';
    const ALIGO_TEMPLATE_CACHE_KEY = 'aligo-template-cache-key';

    private $client;
    private $appId;
    private $appKey;
    private $smsFrom;
    private $kakaoKey;
    private $token;

    /**
     * AligoKakaoClient constructor.
     * @param string $appId
     * @param string $appKey
     * @param string $smsFrom
     * @param string $kakaoKey
     * @throws CouldNotSendNotification
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    public function __construct(string $appId, string $appKey, string $smsFrom, string $kakaoKey)
    {
        $this->client = new GuzzleClient([
            'base_uri' => 'https://kakaoapi.aligo.in'
        ]);
        $this->appId = $appId;
        $this->appKey = $appKey;
        $this->smsFrom = $smsFrom;
        $this->kakaoKey = $kakaoKey;
        $this->token = $this->getAccessToken();
    }

    /**
     * @param AligoKakaoMessage $message
     * @return mixed
     * @throws CouldNotSendNotification
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send(AligoKakaoMessage $message)
    {
        $dto = $this->getMatchingKakaoTemplate($message->code);
        $payload = [
            'token' => $this->token,
            'senderkey' => $this->kakaoKey,
            'sender' => $this->smsFrom,
            'tpl_code' => $message->code,
            'receiver_1' => $message->to,
            'subject_1' => 'n/a', // Aligo specs mentioned this field as required but in fact it's not being used. Wtf?
            'message_1' => $this->compositeKakaoMessageWithReplacements($dto->getMessage(), $message->replacements)
        ];
        if (! empty($dto->getButton())) {
            $payload = array_merge([
                'button_1' => json_encode(['button' => $dto->getButton()])
            ], $payload);
        }
        if ($message->debug) {
            $payload = array_merge([
                'testMode' => 'Y'
            ], $payload);
        }

        $response = $this->call("/akv10/alimtalk/send/", $payload);

        return json_decode((string) $response->getBody(), false);
    }

    // ================================================================================
    // private methods
    // ================================================================================

    /**
     * @param $message
     * @param $replacements
     * @return string
     * @throws CouldNotSendNotification
     */
    private function compositeKakaoMessageWithReplacements($message, $replacements): string
    {
        $string = preg_replace('/(\#\{[^\}]+\})/', '?', $message, -1, $count);
        if (count($replacements) !== $count) {
            throw CouldNotSendNotification::templateMessageFormatUnmatched();
        }

        return Str::replaceArray('?', $replacements, $string);
    }

    /**
     * @param string $code
     * @return KakaoTemplateDto
     * @throws CouldNotSendNotification
     */
    private function getMatchingKakaoTemplate(string $code): KakaoTemplateDto
    {
        $dto = collect($this->getKakaoTemplateList())
            ->first(function (KakaoTemplateDto $dto) use ($code) {
                return $dto->getCode() === $code;
            });
        if (! $dto) {
            throw CouldNotSendNotification::templateNotFound();
        }

        return $dto;
    }

    /**
     * @return array
     */
    private function getKakaoTemplateList(): array
    {
        return Cache::remember(self::ALIGO_TEMPLATE_CACHE_KEY, 360, function () {
            try {
                $response = $this->call('/akv10/template/list', [
                    'token' => $this->token,
                    'senderkey' => $this->kakaoKey,
                ]);
                $data = json_decode((string) $response->getBody(), false);
            } catch (\Throwable $exception) {
                throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
            }

            return collect($data->list)
                ->map(function ($item) {
                    return new KakaoTemplateDto(
                        $item->templtCode,
                        $item->templtContent,
                        collect($item->buttons)
                            ->map(function ($item) {
                                return new KakaoTemplateButtonDto(
                                    $item->name,
                                    $item->linkType,
                                    $item->linkTypeName,
                                    $item->linkMo,
                                    $item->linkPc,
                                    $item->linkIos,
                                    $item->linkAnd
                                );
                            })
                            ->toArray()
                    );
                })
                ->toArray();
        });
    }

    /**
     * A centralized cache layer wouldn't work because of the way Aligo generate the token.
     * Find out more information at https://smartsms.aligo.in/shop/kakaoapispec.html
     *
     * @return string
     * @throws CouldNotSendNotification
     * @throws \Illuminate\Contracts\Filesystem\FileNotFoundException
     */
    private function getAccessToken(): string
    {
        try {
            $token = Storage::disk('local')->get(self::ALIGO_ACCESS_TOKEN_FILE);
        } catch (\Exception $exception) {
            $token = null;
        }

        if (empty($token)) {
            try {
                $response = $this->call('/akv10/token/create/10/y');
                $data = json_decode((string) $response->getBody(), false);
            } catch (\Throwable $exception) {
                throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
            }
            Storage::disk('local')->put(self::ALIGO_ACCESS_TOKEN_FILE, $data->token);

            return $data->token;
        }

        return $token;
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
            'form_params' => array_merge([
                'userid' => $this->appId,
                'apikey' => $this->appKey,
            ], $body)
        ]);
    }
}
