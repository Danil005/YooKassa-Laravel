<?php

return [
    /**
     * API Token from YooKassa
     * GET API KEY: https://yookassa.ru/my/merchant/integration/api-keys
    */
    'token'   => env('YOOKASSA_TOKEN', ''),

    # ID Shop Merchant
    'shop_id' => env('YOOKASSA_ID', ''),

    # Redirect URI
    'redirect_uri' => env('YOOKASSA_REDIRECT', '')
];