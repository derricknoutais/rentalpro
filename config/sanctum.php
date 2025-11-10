<?php

use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\EncryptCookies;

$defaultStatefulDomains = [
    'localhost',
    '127.0.0.1',
    'localhost:82',
    'rentalpro.test',
];

$appUrl = env('APP_URL');

if ($appUrl) {
    $host = parse_url($appUrl, PHP_URL_HOST);
    $port = parse_url($appUrl, PHP_URL_PORT);

    if ($host) {
        $defaultStatefulDomains[] = $port ? "{$host}:{$port}" : $host;
    }
}

$envDomains = array_filter(array_map('trim', explode(',', env('SANCTUM_STATEFUL_DOMAINS', ''))));

return [
    'stateful' => array_values(array_unique(array_filter(array_merge($envDomains, $defaultStatefulDomains)))),

    'guard' => ['web'],

    'expiration' => null,

    'middleware' => [
        'verify_csrf_token' => VerifyCsrfToken::class,
        'encrypt_cookies' => EncryptCookies::class,
    ],
];
