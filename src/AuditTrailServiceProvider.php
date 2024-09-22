<?php

/**
 * Davidiwezulu/AuditTrail
 *
 * @license MIT © 2021 David Iwezulu
 */

namespace Davidiwezulu\AuditTrail;

use Illuminate\Support\ServiceProvider;

class AuditTrailServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerMigrations();
    }

    /**
     * Register the package's migrations for publishing.
     *
     * @return void
     */
    protected function registerMigrations(): void
    {
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations'),
        ], 'audittrail-migrations');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        // You may register bindings or package services here
    }
}
