<?php

namespace JinseokOh\Aligo;

use Illuminate\Notifications\Notification;
use JinseokOh\Aligo\Exceptions\CouldNotSendNotification;

class AligoTextChannel
{
    protected $client;

    public function __construct(AligoTextClient $client)
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
     * @throws CouldNotSendNotification
     */
    public function send($notifiable, Notification $notification)
    {
        if (! $to = $notifiable->routeNotificationFor('AligoText')) {
            throw CouldNotSendNotification::missingTo();
        }

        /** @var AligoTextMessage $message */
        $message = $notification->toAligoText($notifiable);
        $message->to($to);

        try {
            return $this->client->send($message);
        } catch (\Throwable $exception) {
            throw CouldNotSendNotification::serviceRespondedWithAnError($exception);
        }
    }
}
