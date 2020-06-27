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
        $this->type = $this->detectMessageType($content);

        return $this;
    }

    /**
     * Force truncating the message content for a short message.
     *
     * @return $this
     */
    public function short()
    {
        $message = $this->truncate($this->content);
        $this->content($message);

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
     * @param string $message
     * @return string
     */
    private function detectMessageType(string $message): string
    {
        return $this->kr_strlen($message) > self::MAX_LENGTH_FOR_SHORT_MESSAGE
            ? self::LONG_MESSAGE : self::SHORT_MESSAGE;
    }

    /**
     * @param string $message
     * @return string
     */
    private function truncate(string $message): string
    {
        while ($this->kr_strlen($message) > self::MAX_LENGTH_FOR_SHORT_MESSAGE) {
            $message = mb_substr($message, 0, -1);
        }

        return $message;
    }

    /**
     * @param string $message
     * @return int
     */
    private function kr_strlen($message): int
    {
        $multiByteStringsOnly = preg_replace(
            "/[[:alnum:]]|[[:space:]]|[[:punct:]]/",
            "",
            $message
        );

        return mb_strlen($message) + mb_strlen($multiByteStringsOnly);
    }
}
