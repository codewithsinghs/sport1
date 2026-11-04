<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Association
    |--------------------------------------------------------------------------
    |
    | When no association can be resolved from the authenticated user or a
    | registered public domain, the application may fall back to this trusted
    | server-side configuration. This is intended for local development and
    | single-association deployments.
    |
    */

    'default_slug' => env('ASSOCIATION_DEFAULT_SLUG'),

    'allow_config_fallback' => (bool) env('ASSOCIATION_ALLOW_CONFIG_FALLBACK', false),

];
