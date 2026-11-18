<?php

return [
    'cache_key' => 'site_settings',
    'cache_duration' => 60 * 24, // 24 hours
    'defaults' => [
        'name' => env('APP_NAME', 'Liberu Real Estate'),
        'currency' => '£',
        'default_language' => 'en',
        'address' => '',
        'country' => '',
        'email' => '',
        'phone_01' => '',
        'phone_02' => '',
        'phone_03' => '',
        'phone_04' => '',
        'facebook' => '',
        'twitter' => '',
        'github' => '',
        'youtube' => '',
    ],
];