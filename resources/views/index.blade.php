<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Activity Logs</title>
    <style>
        :root {
            color-scheme: light dark;
            --bg: #f6f7f9;
            --surface: #ffffff;
            --border: #e5e7eb;
            --text: #111827;
            --muted: #6b7280;
            --accent: #4f46e5;
        }
        @media (prefers-color-scheme: dark) {
            :root {
                --bg: #0f1115;
                --surface: #171a21;
                --border: #2a2e37;
                --text: #e5e7eb;
                --muted: #9ca3af;
                --accent: #818cf8;
            }
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            background: var(--bg);
            color: var(--text);
            padding: 2rem 1.25rem;
        }
        .wrap { max-width: 1080px; margin: 0 auto; }
        h1 { font-size: 1.375rem; margin: 0 0 1.25rem; }
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 1.25rem;
            margin-bottom: 1.25rem;
        }
        form.filters {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
            align-items: end;
        }
        .field { display: flex; flex-direction: column; gap: 0.3rem; min-width: 150px; }
        .field label { font-size: 0.75rem; color: var(--muted); }
        .field select, .field input {
            border: 1px solid var(--border);
            background: var(--bg);
            color: var(--text);
            border-radius: 8px;
            padding: 0.45rem 0.6rem;
            font-size: 0.875rem;
        }
        .actions-row { display: flex; gap: 0.5rem; }
        button, .btn {
            border: none;
            background: var(--accent);
            color: #fff;
            padding: 0.5rem 0.9rem;
            border-radius: 8px;
            font-size: 0.875rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
        }
        .btn-ghost {
            background: transparent;
            color: var(--muted);
            border: 1px solid var(--border);
        }
        table { width: 100%; border-collapse: collapse; font-size: 0.875rem; }
        th, td { text-align: left; padding: 0.6rem 0.75rem; border-bottom: 1px solid var(--border); }
        th { color: var(--muted); font-weight: 600; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.03em; }
        tbody tr:hover { background: color-mix(in srgb, var(--accent) 6%, transparent); }
        .badge {
            display: inline-block;
            padding: 0.2rem 0.55rem;
            border-radius: 999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        .badge-created { background: #dcfce7; color: #166534; }
        .badge-updated { background: #dbeafe; color: #1e40af; }
        .badge-deleted { background: #fee2e2; color: #991b1b; }
        .badge-restored { background: #ede9fe; color: #5b21b6; }
        .muted { color: var(--muted); }
        .empty { text-align: center; padding: 2.5rem 1rem; color: var(--muted); }
        .table-wrap { overflow-x: auto; }
        .pager {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-top: 1rem;
            font-size: 0.875rem;
        }
        .pager-btn { padding: 0.4rem 0.8rem; border-radius: 8px; border: 1px solid var(--border); text-decoration: none; color: var(--text); }
        .pager-btn.disabled { color: var(--muted); pointer-events: none; opacity: 0.5; }
    </style>
</head>
<body>
<div class="wrap">
    <h1>Activity Logs</h1>

    <div class="card">
        <form class="filters" method="GET">
            <div class="field">
                <label for="action">Action</label>
                <select name="action" id="action">
                    <option value="">All</option>
                    @foreach ($actions as $action)
                        <option value="{{ $action }}" {{ ($filters['action'] ?? null) === $action ? 'selected' : '' }}>{{ ucfirst($action) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="subject_type">Subject Type</label>
                <select name="subject_type" id="subject_type">
                    <option value="">All</option>
                    @foreach ($subjectTypes as $type)
                        <option value="{{ $type }}" {{ ($filters['subject_type'] ?? null) === $type ? 'selected' : '' }}>{{ class_basename($type) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="causer_type">Causer Type</label>
                <select name="causer_type" id="causer_type">
                    <option value="">All</option>
                    @foreach ($causerTypes as $type)
                        <option value="{{ $type }}" {{ ($filters['causer_type'] ?? null) === $type ? 'selected' : '' }}>{{ class_basename($type) }}</option>
                    @endforeach
                </select>
            </div>

            <div class="field">
                <label for="from">From</label>
                <input type="date" name="from" id="from" value="{{ $filters['from'] ?? '' }}">
            </div>

            <div class="field">
                <label for="to">To</label>
                <input type="date" name="to" id="to" value="{{ $filters['to'] ?? '' }}">
            </div>

            <div class="actions-row">
                <button type="submit">Filter</button>
                <a class="btn btn-ghost" href="{{ url()->current() }}">Reset</a>
            </div>
        </form>
    </div>

    <div class="card">
        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>When</th>
                    <th>Causer</th>
                    <th>Action</th>
                    <th>Subject</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($logs as $log)
                    @php
                        $describe = function ($related, $type, $id) {
                            if ($related) {
                                foreach (['name', 'title', 'email'] as $attribute) {
                                    if (! empty($related->{$attribute})) {
                                        return $related->{$attribute};
                                    }
                                }
                            }

                            return class_basename($type) . ' #' . $id;
                        };
                    @endphp
                    <tr>
                        <td class="muted">{{ $log->created_at?->format('Y-m-d H:i') }}</td>
                        <td>{{ $describe($log->creatorable, $log->creatorable_type, $log->creatorable_id) }}</td>
                        <td><span class="badge badge-{{ $log->action }}">{{ ucfirst($log->action) }}</span></td>
                        <td>{{ $describe($log->actionable, $log->actionable_type, $log->actionable_id) }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="empty">No activity logs found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        @if ($logs->total() > 0)
            <div class="pager">
                @if ($logs->onFirstPage())
                    <span class="pager-btn disabled">&laquo; Prev</span>
                @else
                    <a class="pager-btn" href="{{ $logs->previousPageUrl() }}">&laquo; Prev</a>
                @endif

                <span class="muted">Page {{ $logs->currentPage() }} of {{ $logs->lastPage() }} &middot; {{ $logs->total() }} total</span>

                @if ($logs->hasMorePages())
                    <a class="pager-btn" href="{{ $logs->nextPageUrl() }}">Next &raquo;</a>
                @else
                    <span class="pager-btn disabled">Next &raquo;</span>
                @endif
            </div>
        @endif
    </div>
</div>
</body>
</html>
