<?php

// config for Ybreaka98/EbtekarDCB
return [
    'base_url' => env('EBTEKAR_BASE_URL', 'https://connexlive.ebtekarcloud.com/external-api/'),
    'email' => env('EBTEKAR_DCB_EMAIL', ''),
    'password' => env('EBTEKAR_DCB_PASSWORD', ''),
    'apis' => [
        'auth-login' => [
            'url' => 'auth-login',
            'method' => 'POST',
        ],
        'protected-script' => [
            'url' => 'protected-script',
            'method' => 'POST',
        ],
        'login' => [
            'url' => 'login',
            'method' => 'POST',
        ],
        'login-confirm' => [
            'url' => 'login-confirm',
            'method' => 'POST',
        ],
        'upgrade' => [
            'url' => 'upgrade',
            'method' => 'POST',
        ],
        'upgrade-confirm' => [
            'url' => 'upgrade-confirm',
            'method' => 'POST',
        ],
        'subscriber-details' => [
            'url' => 'subscriber-details',
            'method' => 'GET',
        ],
        'direct-unsubscribe' => [
            'url' => 'direct-unsubscribe',
            'method' => 'POST',
        ],
        'unsubscribe' => [
            'url' => 'unsubscribe',
            'method' => 'POST',
        ],
        'unsubscribe_confirm' => [
            'url' => 'unsubscribe_confirm',
            'method' => 'POST',
        ],
        'subscription-activation' => [
            'url' => 'subscription-activation',
            'method' => 'POST',
        ],
        'subscription-activation-confirm' => [
            'url' => 'subscription-activation-confirm',
            'method' => 'POST',
        ],
        'buy-product' => [
            'url' => 'buy-product',
            'method' => 'POST',
        ],
        'buy-product-confirm' => [
            'url' => 'buy-product-confirm',
            'method' => 'POST',
        ],
    ],
];
