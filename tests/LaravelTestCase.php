<?php

namespace Roles\Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Orchestra\Testbench\TestCase as Orchestra;
use Roles\RolesServiceProvider;

/**
 * Class LaravelTestCase
 *
 * @package Andrewtweber\Tests
 */
abstract class LaravelTestCase extends Orchestra
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();

        $this->loadLaravelMigrations();
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return string[]
     */
    protected function getPackageProviders($app)
    {
        return [
            RolesServiceProvider::class,
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
    }
}
