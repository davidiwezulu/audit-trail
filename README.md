# Davidiwezulu/AuditTrail

[![Latest Version on Packagist](https://img.shields.io/packagist/v/Davidiwezulu/AuditTrail.svg?style=flat-square)](https://packagist.org/packages/Davidiwezulu/AuditTrail)
[![Total Downloads](https://img.shields.io/packagist/dt/Davidiwezulu/AuditTrail.svg?style=flat-square)](https://packagist.org/packages/Davidiwezulu/AuditTrail)
[![Build Status](https://img.shields.io/github/actions/workflow/status/Davidiwezulu/AuditTrail/tests.yml?branch=main&style=flat-square)](https://github.com/Davidiwezulu/AuditTrail/actions)
[![License](https://img.shields.io/github/license/Davidiwezulu/AuditTrail.svg?style=flat-square)](https://github.com/Davidiwezulu/AuditTrail/blob/main/LICENSE.md)

![Laravel Version](https://img.shields.io/badge/Laravel-9.x-orange?style=flat-square&logo=laravel)

## Overview

**Davidiwezulu/AuditTrail** is a Laravel package that automatically tracks changes to Eloquent models, providing:

- **Versioning**: Each modification to a model is stored as a new version.
- **Rollback Capabilities**: Easily revert any model to a previous version.
- **Detailed Logs**: Record what was changed, when, and by whom (with user tracking).

This package is designed for applications that require comprehensive audit trails, ensuring data integrity and easy recovery of historical data.

## Features

- Automatically tracks `created`, `updated`, and `deleted` events for any Eloquent model.
- Maintains a log of both the old and new values of changed attributes.
- Supports rollback to any previous version of a model.
- Logs changes made by authenticated users.
- Detailed audit history accessible via relationships.

## Installation

### Requirements

- Laravel 9.x or newer
- PHP 8.0 or newer

### Step 1: Install via Composer

Run the following command to install the package:

```bash
composer require Davidiwezulu/audittrail
```
### Step 2: Publish and Run Migrations

Publish the migration file for the audit_trails table:

```bash
php artisan vendor:publish --tag=audittrail-migrations
```
Run the migrations:
```bash
php artisan migrate
```

## Configuration
This package comes with default settings, but you can publish the configuration file if you'd like to customize the package further:
```bash
php artisan vendor:publish --provider="Davidiwezulu\AuditTrail\AuditTrailServiceProvider" --tag="config"
```

## Usage
### Step 1: Use the Auditable Trait in Your Model
To enable audit tracking for a model, use the Auditable trait in your Eloquent model:
```bash
<?php

namespace App\Models;

use Davidiwezulu\AuditTrail\Traits\Auditable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use Auditable;

    protected $fillable = ['title', 'content'];
}
```
Once the trait is included, the package will automatically log created, updated, and deleted events for this model.

### Step 2: Accessing Audit Trails
You can retrieve the audit trail for a specific model using the auditTrails() relationship:
```bash
$post = Post::find(1);
$auditTrails = $post->auditTrails;

foreach ($auditTrails as $audit) {
    echo $audit->event . ': ' . json_encode($audit->new_values) . PHP_EOL;
}
```

### Step 3: Rollback to a Previous Version
To rollback a model to a previous version, you can use the rollbackTo() method:

```bash
$post = Post::find(1);
$auditTrailId = 5; // The ID of the audit trail entry to rollback to
$post->rollbackTo($auditTrailId);
```
The model will be restored to the old values recorded in the selected audit trail.

## Events
By default, the package listens to the created, updated, and deleted events. You can modify which events are tracked by overriding the getAuditEvents method in your model:
```bash
protected static function getAuditEvents()
{
    return ['created', 'updated']; // Only log created and updated events
}
```
## Testing
This package comes with unit tests to ensure functionality. To run the tests, use the following command within the package itself:
```bash
vendor/bin/phpunit
```

## Test Scenarios

### 1. Model Changes:
Verify that `created`, `updated`, and `deleted` events generate appropriate audit logs.

### 2. Rollback:
Ensure models can be reverted to previous versions using the rollback feature.

### 3. Relationships:
Confirm that the audit trail's morph relationships are correctly established between auditable models and users.

---

## Contribution Guidelines

If you wish to contribute to this package, please follow these steps:

1. Fork the repository.
2. Create a feature branch:
   ```bash
   git checkout -b feature/new-feature
   ```
3. Commit your changes:
   ```bash
   git commit -m 'Add new feature'
   ```
4. Push to the branch:
   ```bash
   git push origin feature/new-feature
   ```
5. Open a pull request, and provide a description of your changes.

### All contributions must adhere to the following guidelines:

- Ensure code quality by following the Laravel style guide.
- Add unit tests for any new features or bug fixes.
- Update the README with any changes that impact usage.


## License

This package is open-source software licensed under the [MIT License](LICENSE).
