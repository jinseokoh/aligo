<?php

namespace JinseokOh\Aligo\Tests;

use Illuminate\Notifications\Notification;
use JinseokOh\Aligo\AligoKakaoMessage;

class TestKakaoNotification extends Notification
{
    public function toAligoKakao($notifiable)
    {
        return (new AligoKakaoMessage())
            ->code('WHATEVER')
            ->replacements(['who', 'the', 'hell', 'are', 'you']);
    }
}
