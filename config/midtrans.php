<?php

return [
    'is_production' => env('MIDTRANS_IS_PRODUCTION', false),
    'server_key' => env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-6V2F20Q6S2uqtCzAn8bxn23R'),
    'client_key' => env('MIDTRANS_CLIENT_KEY', 'SB-Mid-client-_S4uNUfgVshloPJU'),
    'is_sanitized' => env('MIDTRANS_IS_SANITIZED', true),
    'is_3ds' => env('MIDTRANS_IS_3DS', true),
];