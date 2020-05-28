<?php

namespace JinseokOh\Aligo;

use Illuminate\Notifications\Notification;
use NotificationChannels\Aligo\Exceptions\CouldNotSendNotification;

class AligoKakaoChannel
{
    protected $client;

    public function __construct(AligoKakaoClient $client)
    {
        $this->client = $client;
    }

    /**
     * Send the given notification.
     *
     * @param  mixed $notifiable
     * @param  \Illuminate\Notifications\Notification $notification
     * @return mixed
     *
     * @throws \NotificationChannels\Aligo\Exceptions\CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('AligoKakao')) {
            throw CouldNotSendNotification::missingTo();
        }

        /** @var AligoKakaoMessage $message */
        $message = $notification->toAligo($notifiable);
        $message->to($to);

        try {
            return $this->client->send($message);
        } catch (\Throwable $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        }
    }
}
