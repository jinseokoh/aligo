<?php

namespace JinseokOh\Aligo\Test;

use Illuminate\Notifications\Notifiable;

class TestNotifiable
{
    use Notifiable;

    /**
     * @return string
     */
    public function routeNotificationForAligoText()
    {
        return '010-9486-7415';
    }

    /**
     * @return string
     */
    public function routeNotificationForAligoKakao()
    {
        return '010-9486-7415';
    }
}
