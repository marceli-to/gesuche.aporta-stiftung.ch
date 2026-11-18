<?php

use Illuminate\Support\Facades\Facade;

return [

    'name' => env('APP_NAME', 'Laravel'),

    'env' => env('APP_ENV', 'production'),

    'debug' => (bool) env('APP_DEBUG', false),

    'url' => env('APP_URL', 'http://localhost'),

    'asset_url' => env('ASSET_URL'),

    'timezone' => 'UTC',

    'locale' => 'de',

    'fallback_locale' => 'en',

    'faker_locale' => 'de_CH',

    'cipher' => 'AES-256-CBC',

    'key' => env('APP_KEY'),

    'previous_keys' => [
        ...array_filter(
            explode(',', (string) env('APP_PREVIOUS_KEYS', ''))
        ),
    ],

    'maintenance' => [
        'driver' => env('APP_MAINTENANCE_DRIVER', 'file'),
        'store' => env('APP_MAINTENANCE_STORE', 'database'),
    ],

    // Service providers are registered in bootstrap/providers.php,
    // package providers (e.g. DomPDF) via package discovery.

    'aliases' => Facade::defaultAliases()->merge([
        'AppHelper' => App\Helpers\AppHelper::class,
        'FormatHelper' => App\Helpers\FormatHelper::class,
    ])->toArray(),

];
