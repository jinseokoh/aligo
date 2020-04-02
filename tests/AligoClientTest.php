<?php

namespace JinseokOh\Aligo\Test;

use JinseokOh\Aligo\AligoClientContract;
use JinseokOh\Aligo\ClientFactory;

class AligoClientTest extends TestCase
{
    public function testClientSendsKakaoMessage()
    {
        /** @var AligoClientContract $client */
        $client = ClientFactory::create('kakao');

        $response = $client->sendMessage(
            "인증번호는 123456 입니다. 어뮤즈트래블",
            "01094867415",
            true
        );

        $this->assertEquals(0, $response->code);
    }

    public function testClientSendsSmsMessage()
    {
        /** @var AligoClientContract $client */
        $client = ClientFactory::create('sms');

        $response = $client->sendMessage(
            "Hello from Aligo text messaging test.",
            "01094867415",
            true
        );

        $this->assertEquals('success', $response->message);
    }
}
