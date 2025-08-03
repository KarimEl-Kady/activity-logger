<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Models to Log
    |--------------------------------------------------------------------------
    | Add the models that should be tracked for create/update/delete.
    | Example:
    | App\Models\Post::class,
    | App\Models\Order::class,
    */
    'targets' => [
        // App\Models\Post::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Actions to Log
    |--------------------------------------------------------------------------
    */
    'log_actions' => ['created', 'updated', 'deleted'],
];
