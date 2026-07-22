<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Guards to Check for the Causer
    |--------------------------------------------------------------------------
    | The package looks through these guards, in order, for the first
    | authenticated user and records them as the causer of the action.
    | Add every guard your app authenticates users against (web, api, admin...).
    */
    'guards' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Actions to Log
    |--------------------------------------------------------------------------
    | Any model using the HasActivityLogs trait is observed automatically.
    | Choose which of its lifecycle events should be recorded.
    */
    'log_actions' => ['created', 'updated', 'deleted', 'restored'],

    /*
    |--------------------------------------------------------------------------
    | Report UI
    |--------------------------------------------------------------------------
    | A ready-made, filterable report page. Disabled by default. When you
    | enable it, add your own auth middleware (e.g. 'auth') — the package
    | has no opinion on who should be allowed to view the logs.
    */
    'ui' => [
        'enabled' => env('ACTIVITY_LOGGER_UI_ENABLED', false),
        'path' => 'activity-logs',
        'middleware' => ['web'],
    ],
];
