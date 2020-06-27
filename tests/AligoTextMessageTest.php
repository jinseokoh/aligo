<?php

namespace JinseokOh\Aligo\Tests;

use JinseokOh\Aligo\AligoTextMessage;

class AligoTextMessageTest extends TestCase
{
    /** @test */
    public function it_can_automatically_detect_text_message_type()
    {
        $message = (new AligoTextMessage())
            ->content('Chinese online influence operation attempts to divide South Korean society are gaining increasing attention.');

        $this->assertEquals('LMS', $message->type);
    }

    /** @test */
    public function it_can_convert_long_text_to_short_one()
    {
        $message = (new AligoTextMessage())
            ->content('친구 신청을 보냈습니다 친구 신청을 보냈습니다 친구 신청을 보냈습니다 친구 신청을 보냈습니다 친구 신청을 보냈습니다 친구 신청을 보냈습니다')
          ->short();

        $this->assertEquals('친구 신청을 보냈습니다 친구 신청을 보냈습니다 친구 신청을 보냈습니다 친구 신청을 보냈습니', $message->content);
    }
}
