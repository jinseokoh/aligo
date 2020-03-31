<?php

namespace JinseokOh\Aligo\Test;

use JinseokOh\Aligo\AligoHandler;

class AligoHandlerTest extends TestCase
{
    public function testClientSendsShortTextMessage()
    {
        $handler = app(AligoHandler::class);

        $response = $handler->sendSMS('hello from laravel', ['01094867415'], [], true);

        $this->assertEquals('success', $response->message);
    }

    public function testClientRequestsRemainingCredits()
    {
        $handler = app(AligoHandler::class);

        $response = $handler->remain();

        $this->assertEquals('success', $response->message);
    }
}
