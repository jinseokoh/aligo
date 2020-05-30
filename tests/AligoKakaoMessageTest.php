<?php

namespace JinseokOh\Aligo\Test;

use JinseokOh\Aligo\AligoKakaoMessage;

class AligoKakaoMessageTest extends TestCase
{
    /** @test */
    public function it_sets_default_flags_to_off()
    {
        $message = (new AligoKakaoMessage())
            ->code('test')
            ->replacements(['123456']);

        $this->assertEquals(false, $message->debug);
        $this->assertEquals(false, $message->fallback);
    }
}
