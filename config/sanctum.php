<?php

use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\EncryptCookies;

return [
    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', implode(',', ['localhost', '127.0.0.1', 'localhost:82', 'rentalpro.test']))),

    'guard' => ['web'],

    'expiration' => null,

    'middleware' => [
        'verify_csrf_token' => VerifyCsrfToken::class,
        'encrypt_cookies' => EncryptCookies::class,
    ],
];
