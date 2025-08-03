<?php

namespace Elkady\ActivityLogger\Traits;

use Elkady\ActivityLogger\Models\ActivityLog;

trait HasActivityLogs
{
    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'creatorable');
    }
}