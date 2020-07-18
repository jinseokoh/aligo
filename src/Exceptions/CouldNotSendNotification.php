<?php

namespace JinseokOh\Aligo\Exceptions;

use Exception;

class CouldNotSendNotification extends Exception
{
    /**
     * @param \Throwable $exception
     * @return static
     */
    public static function serviceRespondedWithAnError(\Throwable $exception): self
    {
        return new static(
            "The communication with Aligo failed. Reason: {$exception->getMessage()}",
            $exception->getCode(),
            $exception
        );
    }

    /**
     * @return static
     */
    public static function templateMessageFormatUnmatched(): self
    {
        return new static('The number of template slots is not matched with the given replacements.');
    }

    /**
     * @return static
     */
    public static function templateNotFound(): self
    {
        return new static('Not template associated with the code is found.');
    }

    /**
     * @return static
     */
    public static function missingTo(): self
    {
        return new static('Notification was not sent. Missing `to` number.');
    }
}
