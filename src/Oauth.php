<?php

namespace Fiks\YooKassa;

use Illuminate\Http\RedirectResponse;

class Oauth
{
    /**
     * Redirect For Get Code
     *
     * @return RedirectResponse
     */
    public function redirect()
    {
        $client_id = env('YOOKASSA_CLIENT_ID', null);

        if( !$client_id )
            die('YOOKASSA_CLIENT_ID not exist');

        return redirect()
            ->to('https://yookassa.ru/oauth/v2/authorize?client_id='.$client_id.'&response_type=code')
            ->send();
    }
}