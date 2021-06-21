<?php

namespace Fiks\YooKassa;

use Illuminate\Support\Facades\Facade as BaseFacade;

class Facade extends BaseFacade
{
    public static function getFacadeAccessor()
    {
        return YooKassaApi::class;
    }
}