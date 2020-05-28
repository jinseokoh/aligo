<?php

namespace JinseokOh\Aligo\Test;

use JinseokOh\Aligo\AligoTextChannel;
use JinseokOh\Aligo\AligoTextClient;
use JinseokOh\Aligo\AligoTextMessage;
use Mockery;

class AligoTextChannelTest extends TestCase
{
    protected $client;

    protected $channel;

    protected $notification;

    protected $message;

    public function setUp(): void
    {
        parent::setUp();
        \Hamcrest\Util::registerGlobalFunctions();
        $this->client = Mockery::mock(AligoTextClient::class);
        $this->channel = new AligoTextChannel($this->client);
        $this->message = Mockery::mock(AligoTextMessage::class);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    /** @test */
    public function it_can_send_a_notification()
    {
        $this->client
            ->shouldReceive('send')
            ->once()
            ->with(\Mockery::on(function ($arguments) {
                return is_a($arguments, AligoTextMessage::class);
            }));

        $notifiable = new TestNotifiable();
        $notification = new TestTextNotification();
        $this->channel->send($notifiable, $notification);
    }
}
