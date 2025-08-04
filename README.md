# Laravel Activity Logger

A Laravel package for automatically logging authenticated users' actions (create, update, delete) on configured models.

---

## Features
- 🔄 **Automatic Logging** via Laravel Observers — no manual calls required
- ⚙️ **Configurable** — choose which models to track
- 🔗 **Polymorphic Relations** — works with multiple auth models
- 🛠 **Easy Integration** — install, configure, done
- 🚫 **No Modification Needed** for target models

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

## Usage :
In any auth model you want to follow steps you have to use the HasActivityLogs like

use Elkady\ActivityLogger\Traits\HasActivityLogs;
```php
class User extends Authenticatable
{
    use HasActivityLogs;
}
```

## Update the Config File
In config/activity-logger.php, list the models you want to track:
for example 
```php
return [
    'targets' => [
        App\Models\Post::class,
        App\Models\Order::class,
    ],

    'log_actions' => ['created', 'updated', 'deleted'],
];

```
