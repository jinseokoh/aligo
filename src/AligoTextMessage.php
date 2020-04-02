<?php

namespace JinseokOh\Aligo;

class AligoTextMessage
{
    const SHORT_MESSAGE = 'SMS'; // KRW 8.4 per message
    const LONG_MESSAGE = 'LMS'; // KRW 25.9 per message
    const MAX_LENGTH_FOR_SHORT_MESSAGE = 90;

    public $content;
    public $type;
    public $to;
    public $debug;

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * AligoTextMessage constructor.
     */
    public function __construct()
    {
        $this->debug = false;
    }

    /**
     * Set the message content.
     *
     * @param  string  $content
     *
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;
        $this->type = $this->detectMessageType();

        return $this;
    }

    /**
     * Force truncating the message content for a short message.
     *
     * @return $this
     */
    public function short()
    {
        $this->content = $this->truncate(self::MAX_LENGTH_FOR_SHORT_MESSAGE);
        $this->type = self::SHORT_MESSAGE;

        return $this;
    }

    /**
     * Set the phone number the message should be sent to.
     *
     * @param  string  $to
     * @return $this
     */
    public function to($to)
    {
        $this->to = $to;

        return $this;
    }

    /**
     * Enables debug mode.
     * @return $this
     */
    public function debug()
    {
        $this->debug = true;

        return $this;
    }

    // ================================================================================
    // helpers
    // ================================================================================

    /**
     * @return string
     */
    private function detectMessageType(): string
    {
        return $this->getStringLengthInEucKr() > self::MAX_LENGTH_FOR_SHORT_MESSAGE
            ? self::LONG_MESSAGE : self::SHORT_MESSAGE;
    }

    /**
     * @param int $upto
     * @return string
     */
    private function truncate(int $upto): string
    {
        $message = $this->content;
        while ($this->getStringLengthInEucKr() > $upto)	{
            $message = mb_substr($message, 0, -1);
        }

        return $message;
    }

    /**
     * @param string $message
     * @return int
     */
    private function getStringLengthInEucKr(): int
    {
        $multiByteStringOnly = preg_replace(
            "/[[:alnum:]]|[[:space:]]|[[:punct:]]/",
            "",
            $this->content
        );

        return mb_strlen($this->content) + mb_strlen($multiByteStringOnly);
    }
}
