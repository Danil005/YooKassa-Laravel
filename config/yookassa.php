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
    'redirect_uri' => env('YOOKASSA_REDIRECT', ''),

    # Database Migration
    'migration' => [
        # Table Name Database
        'table_name' => env('YOOKASSA_DATABASE_TABLE_NAME', 'yookassa'),
        # Field Foreign Column
        'field_foreign' => env('YOOKASSA_DATABASE_FIELD_FOREIGN', 'user_id'),
        # Field On Table
        'field_on' => env('YOOKASSA_DATABASE_FIELD_ON', 'users'),
        # Field References Column
        'field_references' => env('YOOKASSA_DATABASE_FIELD_REFERENCES', 'id'),
        # Field Delete Type
        'field_delete' => env('YOOKASSA_DATABASE_FIELD_ON_DELETE', 'cascade')
    ]
];