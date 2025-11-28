<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Route Prefix
    |--------------------------------------------------------------------------
    |
    | The prefix for all artisan-gui routes.
    |
    */
    'route_prefix' => env('ARTISAN_GUI_PREFIX', 'artisan-gui'),

    /*
    |--------------------------------------------------------------------------
    | Middleware
    |--------------------------------------------------------------------------
    |
    | Middleware to apply to all artisan-gui routes.
    |
    */
    'middleware' => ['web', 'auth'],

    /*
    |--------------------------------------------------------------------------
    | Allowed Commands Whitelist
    |--------------------------------------------------------------------------
    |
    | List of Artisan commands that are allowed to be executed through the GUI.
    | Commands not in this list will be rejected.
    |
    */
    'allowed_commands' => [
        'cache:clear',
        'config:clear',
        'route:clear',
        'view:clear',
        'optimize:clear',
        'queue:work',
        'queue:restart',
        'migrate:status',
        'db:seed',
        'tinker',
    ],

    /*
    |--------------------------------------------------------------------------
    | Command Parameters
    |--------------------------------------------------------------------------
    |
    | Define allowed parameters for each command.
    | Format: 'command:name' => ['param1', 'param2']
    |
    */
    'command_parameters' => [
        'db:seed' => ['--class'],
        'queue:work' => ['--queue', '--tries', '--timeout'],
        'migrate:status' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Permissions
    |--------------------------------------------------------------------------
    |
    | Define which roles can access the artisan-gui.
    | Set to empty array to allow all authenticated users.
    |
    */
    'allowed_roles' => ['admin', 'devops'],

    /*
    |--------------------------------------------------------------------------
    | Log Storage
    |--------------------------------------------------------------------------
    |
    | Where to store command execution logs.
    |
    */
    'log_storage_path' => storage_path('logs/artisan-gui'),

    /*
    |--------------------------------------------------------------------------
    | Max Execution Time
    |--------------------------------------------------------------------------
    |
    | Maximum execution time in seconds for commands.
    |
    */
    'max_execution_time' => 300,

    /*
    |--------------------------------------------------------------------------
    | Localization
    |--------------------------------------------------------------------------
    |
    | Localization settings for the package.
    |
    */
    'locale' => env('ARTISAN_GUI_LOCALE', null), // null uses app locale

    /*
    |--------------------------------------------------------------------------
    | Auto Translation
    |--------------------------------------------------------------------------
    |
    | Settings for automatic translation generation.
    |
    */
    'auto_translation' => [
        'enabled' => env('ARTISAN_GUI_AUTO_TRANSLATE', false),
        'provider' => env('ARTISAN_GUI_TRANSLATION_PROVIDER', 'google'), // google, deepl, etc.
        'api_key' => env('ARTISAN_GUI_TRANSLATION_API_KEY', null),
        'target_languages' => env('ARTISAN_GUI_TARGET_LANGUAGES', 'fa,ar,es,fr,de'),
    ],
];

