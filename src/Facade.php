<?php

namespace Fiks\YooMoney;

use Illuminate\Support\Facades\Facade as BaseFacade;

class Facade extends BaseFacade
{
    public static function getFacadeAccessor()
    {
        return YooMoneyApi::class;
    }
}