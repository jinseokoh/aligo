<?php

namespace JinseokOh\Aligo\Test;

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
}
