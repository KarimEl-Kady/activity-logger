<?php

namespace Elkady\ActivityLogger\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'creatorable_type',
        'creatorable_id',
        'action',
        'actionable_type',
        'actionable_id'
    ];

    public function creatorable()
    {
        return $this->morphTo();
    }

    public function actionable()
    {
        return $this->morphTo();
    }

    public function scopeForSubject(Builder $query, Model|string $subject): Builder
    {
        if ($subject instanceof Model) {
            return $query->where('actionable_type', $subject->getMorphClass())
                ->where('actionable_id', $subject->getKey());
        }

        return $query->where('actionable_type', (new $subject())->getMorphClass());
    }

    public function scopeCausedBy(Builder $query, Model|string $causer): Builder
    {
        if ($causer instanceof Model) {
            return $query->where('creatorable_type', $causer->getMorphClass())
                ->where('creatorable_id', $causer->getKey());
        }

        return $query->where('creatorable_type', (new $causer())->getMorphClass());
    }

    public function scopeOfAction(Builder $query, string|array $action): Builder
    {
        return $query->whereIn('action', (array) $action);
    }

    public function scopeBetween(Builder $query, string|DateTimeInterface $from, string|DateTimeInterface $to): Builder
    {
        return $query->whereBetween('created_at', [$from, $to]);
    }
}
