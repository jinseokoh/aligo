<?php

namespace JinseokOh\Aligo\Tests;

use JinseokOh\Aligo\AligoTextChannel;
use JinseokOh\Aligo\AligoTextClient;
use Mockery;

class AligoTextChannelTest extends TestCase
{
    /** @test */
    public function it_can_send_a_notification()
    {
        $client = Mockery::mock(AligoTextClient::class);
        $channel = new AligoTextChannel($client);
        $client
            ->shouldReceive('send')
            ->once()
            ->andReturn(200);
//            ->with(\Mockery::on(function ($arguments) {
//                return is_a($arguments, AligoTextMessage::class);
//            }));

        $notifiable = new TestNotifiable();
        $notification = new TestTextNotification();
        $channel->send($notifiable, $notification);
    }
}
