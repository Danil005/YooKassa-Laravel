<?php

return [
    /**
     * API Token from YooKassa
     * GET API KEY: https://yookassa.ru/my/merchant/integration/api-keys
    */
    'token'   => env('YOOMONEY_TOKEN', ''),

    # ID Shop Merchant
    'shop_id' => env('YOOMONEY_ID', ''),

    # Redirect URI
    'redirect_uri' => env('YOOMONEY_REDIRECT', '')
];