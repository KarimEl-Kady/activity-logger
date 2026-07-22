<?php

namespace Elkady\ActivityLogger\Http\Controllers;

use Elkady\ActivityLogger\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = ActivityLog::query()->with(['creatorable', 'actionable'])->latest();

        if ($action = $request->input('action')) {
            $query->ofAction($action);
        }

        if ($subjectType = $request->input('subject_type')) {
            $query->where('actionable_type', $subjectType);
        }

        if ($causerType = $request->input('causer_type')) {
            $query->where('creatorable_type', $causerType);
        }

        if (($from = $request->input('from')) && ($to = $request->input('to'))) {
            $query->between($from, $to);
        }

        return view('activity-logger::index', [
            'logs' => $query->paginate(20)->withQueryString(),
            'actions' => ActivityLog::query()->select('action')->distinct()->orderBy('action')->pluck('action'),
            'subjectTypes' => ActivityLog::query()->select('actionable_type')->distinct()->orderBy('actionable_type')->pluck('actionable_type'),
            'causerTypes' => ActivityLog::query()->select('creatorable_type')->distinct()->orderBy('creatorable_type')->pluck('creatorable_type'),
            'filters' => $request->only(['action', 'subject_type', 'causer_type', 'from', 'to']),
        ]);
    }
}
