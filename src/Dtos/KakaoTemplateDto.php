<?php

namespace JinseokOh\Aligo\Dtos;

use JsonSerializable;

class KakaoTemplateDto implements JsonSerializable
{
    /**
     * @var string
     */
    private $code;
    /**
     * @var string
     */
    private $message;
    /**
     * @var array
     */
    private $button;

    public function __construct(
        string $code,
        string $message,
        array $button
    )
    {
        $this->code = $code;
        $this->message = $message;
        $this->button = $button;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return array
     */
    public function getButton(): array
    {
        return $this->button;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
            'button' => $this->getButton(),
        ];
    }

    public function toArray(): array
    {
        return [
            'code' => $this->getCode(),
            'message' => $this->getMessage(),
            'button' => $this->getButton(),
        ];
    }
}
