<?php

use Elkady\ActivityLogger\Http\Controllers\ActivityLogController;
use Illuminate\Support\Facades\Route;

Route::middleware(config('activity-logger.ui.middleware', ['web']))
    ->get(config('activity-logger.ui.path', 'activity-logs'), [ActivityLogController::class, 'index'])
    ->name('activity-logger.index');
