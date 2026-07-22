<?php

namespace Elkady\ActivityLogger;

use Illuminate\Support\ServiceProvider;

class ActivityLoggerServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/activity-logger.php' => config_path('activity-logger.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/activity-logger'),
        ], 'views');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'activity-logger');

        if (config('activity-logger.ui.enabled')) {
            $this->loadRoutesFrom(__DIR__ . '/../routes/web.php');
        }
    }
}
