<?php

namespace Elkady\ActivityLogger\Observers;

use Elkady\ActivityLogger\Models\ActivityLog;

class ActivityLogObserver
{
    public function created($model)
    {
        $this->log($model, 'created');
    }

    public function updated($model)
    {
        $this->log($model, 'updated');
    }

    public function deleted($model)
    {
        $this->log($model, 'deleted');
    }

    protected function log($model, $action)
    {
        if (!in_array($action, config('activity-logger.log_actions', []))) {
            return;
        }

        $user = auth()->user();
        if (!$user) {
            return;
        }

        ActivityLog::create([
            'creatorable_type' => get_class($user),
            'creatorable_id'   => $user->id,
            'action'           => $action,
            'actionable_type'  => get_class($model),
            'actionable_id'    => $model->id
        ]);
    }
}