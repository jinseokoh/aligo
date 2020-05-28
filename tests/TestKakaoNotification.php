<?php

namespace JinseokOh\Aligo\Test;

use Illuminate\Notifications\Notification;
use JinseokOh\Aligo\AligoKakaoMessage;

class TestKakaoNotification extends Notification
{
    public function toAligoKakao($notifiable)
    {
        return (new AligoKakaoMessage())
            ->content('Hello world, this is my long message line. where have you been at? I couldn\'t reach out to ya.')
            ->short();
    }
}
