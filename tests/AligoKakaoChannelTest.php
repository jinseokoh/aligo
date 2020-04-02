<?php

namespace JinseokOh\Aligo\Test;

use JinseokOh\Aligo\AligoKakaoChannel;
use JinseokOh\Aligo\AligoKakaoClient;
use Mockery;

class AligoKakaoChannelTest extends TestCase
{
    /** @test */
    public function it_can_send_a_notification()
    {
        $client = Mockery::mock(AligoKakaoClient::class);
        $channel = new AligoKakaoChannel($client);
        $client
            ->shouldReceive('send')
            ->once()
            ->andReturn(200);
//            ->with(\Mockery::on(function ($arguments) {
//                return is_a($arguments, AligoTextMessage::class);
//            }));

        $notifiable = new TestNotifiable();
        $notification = new TestKakaoNotification();
        $channel->send($notifiable, $notification);
    }
}
