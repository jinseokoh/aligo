<?php

namespace JinseokOh\Aligo\Test;

use Illuminate\Notifications\Notification;
use JinseokOh\Aligo\AligoTextChannel;
use JinseokOh\Aligo\AligoTextMessage;

class TestTextNotification extends Notification
{
//    public function via($notifiable)
//    {
//        return [AligoTextChannel::class];
//    }

    public function toAligoText($notifiable)
    {
        return (new AligoTextMessage())
            ->content('South Korea reported the largest number of new coronavirus cases in nearly two months Thursday due to a cluster infection.');
    }
}
