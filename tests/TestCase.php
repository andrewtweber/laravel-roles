<?php

namespace Roles\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;
use Roles\RolesServiceProvider;

/**
 * Class TestCase
 *
 * @package Snaccs\Tests
 */
abstract class TestCase extends Orchestra
{
    /**
     * @param Application $app
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
     * @param Application $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [];
    }

    /**
     * @param Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
    }
}
