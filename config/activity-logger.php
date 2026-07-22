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
];
