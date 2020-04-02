<?php

namespace JinseokOh\Aligo;

class AligoKakaoMessage
{
    public $code;
    public $replacements;
    public $to;
    public $fallback;
    public $debug;

    /**
     * @return static
     */
    public static function create()
    {
        return new static();
    }

    /**
     * AligoKakaoMessage constructor.
     */
    public function __construct()
    {
        $this->fallback = false;
        $this->debug = false;
    }

    /**
     * Set the message code.
     *
     * @param  string  $code
     * @return $this
     */
    public function code($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Set the replacement values to be used in the Kakao message.
     *
     * @param array $replacements
     * @return $this
     */
    public function replacements(array $replacements)
    {
        $this->replacements = $replacements;

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
     * Enables fallback to SMS/LMS mode.
     * @return $this
     */
    public function fallback()
    {
        $this->fallback = true;

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
}
