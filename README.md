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

## Update the Config File
In `config/activity-logger.php`, list the guards to check for the current user and which lifecycle events to record:

```php
return [
    'guards' => ['web', 'api'],

    'log_actions' => ['created', 'updated', 'deleted', 'restored'],
];
```
