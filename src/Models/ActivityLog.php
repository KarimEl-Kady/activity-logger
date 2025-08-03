<?php

namespace Elkady\ActivityLogger\Models;

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
}
