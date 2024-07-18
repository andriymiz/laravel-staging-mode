<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Staging Mode Secret
    |--------------------------------------------------------------------------
    |
    | This value is the secret key that will be used to determine if the
    | staging mode bypass cookie is valid.
    |
    */
    'secret' => env('STAGING_MODE_SECRET'),

    /*
    |--------------------------------------------------------------------------
    | Staging Mode Bypass Cookie Name
    |--------------------------------------------------------------------------
    |
    | This value is the name of the cookie that will be used to bypass
    | the staging mode middleware.
    |
    */
    'cookie_name' => 'laravel_staging',

    /*
    |--------------------------------------------------------------------------
    | Staging Mode Bypass Cookie Expiration
    |--------------------------------------------------------------------------
    |
    | This value is the number of hours that the staging mode bypass cookie
    | will be valid for.
    |
    */
    'cookie_expiration' => 12,

    /*
    |--------------------------------------------------------------------------
    | Except URIs
    |--------------------------------------------------------------------------
    |
    | This value is the array of URIs that should be publicly accessible in
    | staging mode.
    |
    */
    'except' => [],
];
