<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Varsayılan Timeout (saniye)
    |--------------------------------------------------------------------------
    |
    | Curl istekleri için varsayılan timeout süresi
    |
    */
    'timeout' => env('LARACURL_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Connect Timeout (saniye)
    |--------------------------------------------------------------------------
    |
    | Bağlantı için maksimum bekleme süresi
    |
    */
    'connect_timeout' => env('LARACURL_CONNECT_TIMEOUT', 10),

    /*
    |--------------------------------------------------------------------------
    | SSL Doğrulama
    |--------------------------------------------------------------------------
    |
    | SSL sertifikasının doğrulanıp doğrulanmayacağı
    |
    */
    'ssl_verify' => env('LARACURL_SSL_VERIFY', true),

    /*
    |--------------------------------------------------------------------------
    | Yönlendirmeleri Takip Et
    |--------------------------------------------------------------------------
    |
    | HTTP yönlendirmelerinin otomatik olarak takip edilip edilmeyeceği
    |
    */
    'follow_redirects' => env('LARACURL_FOLLOW_REDIRECTS', true),

    /*
    |--------------------------------------------------------------------------
    | Maksimum Yönlendirme
    |--------------------------------------------------------------------------
    |
    | İzlenecek maksimum yönlendirme sayısı
    |
    */
    'max_redirects' => env('LARACURL_MAX_REDIRECTS', 5),

    /*
    |--------------------------------------------------------------------------
    | User Agent
    |--------------------------------------------------------------------------
    |
    | Varsayılan User Agent string
    |
    */
    'user_agent' => env('LARACURL_USER_AGENT', 'LaraCurl/1.0'),

    /*
    |--------------------------------------------------------------------------
    | Varsayılan Headers
    |--------------------------------------------------------------------------
    |
    | Tüm isteklere eklenecek varsayılan header'lar
    |
    */
    'default_headers' => [
        'Accept' => 'application/json',
        'Accept-Encoding' => 'gzip, deflate',
    ],

    /*
    |--------------------------------------------------------------------------
    | Proxy Ayarları
    |--------------------------------------------------------------------------
    |
    | Proxy sunucu ayarları (kullanılmıyorsa null bırakın)
    |
    */
    'proxy' => [
        'enabled' => env('LARACURL_PROXY_ENABLED', false),
        'host' => env('LARACURL_PROXY_HOST', null),
        'port' => env('LARACURL_PROXY_PORT', null),
        'username' => env('LARACURL_PROXY_USERNAME', null),
        'password' => env('LARACURL_PROXY_PASSWORD', null),
    ],

    /*
    |--------------------------------------------------------------------------
    | Verbose Mode
    |--------------------------------------------------------------------------
    |
    | Debug için detaylı çıktı
    |
    */
    'verbose' => env('LARACURL_VERBOSE', false),

    /*
    |--------------------------------------------------------------------------
    | Retry Ayarları
    |--------------------------------------------------------------------------
    |
    | Başarısız istekler için tekrar deneme ayarları
    |
    */
    'retry' => [
        'enabled' => env('LARACURL_RETRY_ENABLED', false),
        'times' => env('LARACURL_RETRY_TIMES', 3),
        'delay' => env('LARACURL_RETRY_DELAY', 1000), // milisaniye
    ],
];
