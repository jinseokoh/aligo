<?php

namespace JinseokOh\Aligo\Exceptions;

use Exception;

class InvalidConfiguration extends Exception
{
    /**
     * @return static
     */
    public static function configurationNotSet(): self
    {
        return new static(
            'In order to send notification via Aligo you need to add credentials in the `aligo` key of `config.services`.'
        );
    }
}
