<?php

namespace JinseokOh\Aligo;

interface AligoClientContract
{
    public function sendMessage(string $message, string $receiverPhoneNumber, bool $testFlag = false);
}
