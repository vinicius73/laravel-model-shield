<?php

namespace Vinicius73\ModelShield\Facades;

use Illuminate\Support\Facades\Facade;

class Shield extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'shield';
    }
}
