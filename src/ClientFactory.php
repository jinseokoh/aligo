<?php

namespace JinseokOh\Aligo;

class ClientFactory
{
    public static function create(string $name)
    {
        if ($name[0] !== '\\') {
            $name = '\\' . __NAMESPACE__ . '\\' . ucfirst($name) . 'Client';
        }

        if (class_exists($name)) {
            return new $name();
        }

        return null;
    }
}
