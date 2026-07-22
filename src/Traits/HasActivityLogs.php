<?php

namespace Elkady\ActivityLogger\Traits;

use Elkady\ActivityLogger\Models\ActivityLog;
use Elkady\ActivityLogger\Observers\ActivityLogObserver;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Model
 */
trait HasActivityLogs
{
    public static function bootHasActivityLogs(): void
    {
        static::observe(ActivityLogObserver::class);
    }

    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'actionable');
    }
}