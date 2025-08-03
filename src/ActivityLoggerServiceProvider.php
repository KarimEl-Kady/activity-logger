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

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        foreach (config('activity-logger.targets', []) as $modelClass) {
            if (class_exists($modelClass)) {
                $modelClass::observe(ActivityLogObserver::class);
            }
        }
    }
}
