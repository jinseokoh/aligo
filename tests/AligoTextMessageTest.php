<?php

namespace JinseokOh\Aligo\Test;

use JinseokOh\Aligo\AligoTextMessage;

class AligoTextMessageTest extends TestCase
{
    /** @var AligoTextMessage */
    protected $message;

    public function setUp(): void
    {
        parent::setUp();

        $this->message = new AligoTextMessage();
    }

    /** @test */
    public function it_can_automatically_detect_text_message_type()
    {
        $message = (new AligoTextMessage())
            ->content('South Korea reported the largest number of new coronavirus cases in nearly two months Thursday due to a cluster infection.');

        $this->assertEquals('LMS', $message->type);
    }
}
