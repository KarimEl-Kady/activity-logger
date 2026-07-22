<?php

namespace Elkady\ActivityLogger\Observers;

use Elkady\ActivityLogger\Models\ActivityLog;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class ActivityLogObserver
{
    public function created(Model $model): void
    {
        $this->log($model, 'created');
    }

    public function updated(Model $model): void
    {
        if (array_diff(array_keys($model->getChanges()), ['updated_at']) === []) {
            return;
        }

        $this->log($model, 'updated');
    }

    public function deleted(Model $model): void
    {
        $this->log($model, 'deleted');
    }

    public function restored(Model $model): void
    {
        $this->log($model, 'restored');
    }

    protected function log(Model $model, string $action): void
    {
        if (!in_array($action, config('activity-logger.log_actions', []))) {
            return;
        }

        $user = $this->resolveCauser();
        if (!$user) {
            return;
        }

        ActivityLog::create([
            'creatorable_type' => get_class($user),
            'creatorable_id'   => $user->getAuthIdentifier(),
            'action'           => $action,
            'actionable_type'  => get_class($model),
            'actionable_id'    => $model->getKey(),
        ]);
    }

    protected function resolveCauser(): ?Authenticatable
    {
        foreach (config('activity-logger.guards', ['web']) as $guard) {
            if ($user = auth()->guard($guard)->user()) {
                return $user;
            }
        }

        return null;
    }
}