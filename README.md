# Laravel Activity Logger

A Laravel package for automatically logging authenticated users' actions (create, update, delete, restore) on any model using the `HasActivityLogs` trait.

---

## Features
- 🔄 **Automatic Logging** via Laravel Observers — no manual calls required
- ⚙️ **Trait-Driven** — add `HasActivityLogs` to any model you want tracked, no config list to maintain
- 🔐 **Multi-Guard Aware** — resolves the causer across web, api, admin, or any custom guard
- 🔗 **Polymorphic Relations** — logs both the causer and the affected model
- 🛠 **Easy Integration** — install, configure, done
- ♻️ **Soft-Delete Aware** — logs `restored` in addition to `created`/`updated`/`deleted`
- 📊 **Filterable Reports** — query logs by subject, causer, action, or date range with chainable Eloquent scopes
- 🖥 **Built-in Report Page** — an optional, ready-made filterable UI, no front-end work required

---

## Installation

Install the package via Composer:

```bash
composer require elkady/activity-logger
```
### After installation you have to publish the config file 
```bash
php artisan vendor:publish --provider="Elkady\ActivityLogger\ActivityLoggerServiceProvider" --tag=config
```
This will create a config file at:
```arduino
config/activity-logger.php
```
then you have to migrate the added database:
```bash
php artisan migrate
```

## Usage
Add `HasActivityLogs` to any model you want to track — that's what turns on logging for it, no config array required:

```php
use Elkady\ActivityLogger\Traits\HasActivityLogs;

class Post extends Model
{
    use HasActivityLogs;
}
```

Every `created`, `updated`, `deleted`, and `restored` event on `Post` is now logged automatically, attributed to whichever guard-authenticated user performed it. Read them back via the relation the trait adds:

```php
$post->activityLogs; // logs recorded about this Post
```

## Reports & Filtering
`ActivityLog` ships with scopes so you can build reports without writing raw queries:

```php
use Elkady\ActivityLogger\Models\ActivityLog;

// everything that happened to a given Post
ActivityLog::forSubject($post)->get();

// everything a given user did
ActivityLog::causedBy($user)->get();

// combine, filter by action, filter by date range, paginate
ActivityLog::forSubject($post)
    ->causedBy($user)
    ->ofAction(['updated', 'deleted'])
    ->between('2026-01-01', '2026-07-22')
    ->latest()
    ->paginate(25);

// filter by type only, without a specific instance
ActivityLog::forSubject(Post::class)->ofAction('deleted')->get();
```

## Report UI
The package includes an optional, self-contained report page — a filter bar (action, subject type, causer type, date range) plus a paginated table — with no front-end setup required. It's off by default. Turn it on in `config/activity-logger.php`:

```php
'ui' => [
    'enabled' => true,
    'path' => 'activity-logs',
    'middleware' => ['web', 'auth'], // add your own auth middleware here
],
```

The package has no opinion on who should be allowed to view the logs, so **always add your own auth middleware** (or a custom gate-based middleware) to `ui.middleware` before enabling it — otherwise the page is open to anyone who can reach the route.

Visit `/activity-logs` (or whatever `ui.path` is set to) to see it. To customize the look, publish the view:

```bash
php artisan vendor:publish --provider="Elkady\ActivityLogger\ActivityLoggerServiceProvider" --tag=views
```

## Update the Config File
In `config/activity-logger.php`, list the guards to check for the current user and which lifecycle events to record:

```php
return [
    'guards' => ['web', 'api'],

    'log_actions' => ['created', 'updated', 'deleted', 'restored'],
];
```
