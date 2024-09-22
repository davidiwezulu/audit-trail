<?php
/**
 * Davidiwezulu/AuditTrail
 *
 * @license MIT
 * @copyright Copyright (c) 2021 David Iwezulu
 */

namespace Davidiwezulu\AuditTrail\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Davidiwezulu\AuditTrail\AuditTrailServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

abstract class TestCase extends OrchestraTestCase
{
    use \Illuminate\Foundation\Testing\RefreshDatabase;

    /**
     * Get the package providers for the test environment.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            AuditTrailServiceProvider::class,
        ];
    }

    /**
     * Define the environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        // Configure the database connection
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    /**
     * Set up the database before each test.
     *
     * @return void
     */
    protected function setUpDatabase(): void
    {
        // Run Laravel's default migrations
        $this->loadLaravelMigrations();

        // Load package migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Create test models' tables
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->timestamps();
        });
    }

    /**
     * Set up the test environment.
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpDatabase();
    }
}
