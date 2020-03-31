<?php

namespace JinseokOh\Aligo;

use Illuminate\Support\Facades\Facade;

class AligoFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Aligo';
    }
}
