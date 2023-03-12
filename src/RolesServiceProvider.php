<?php

namespace Roles;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

/**
 * Class RolesServiceProvider
 *
 * @package Roles
 */
class RolesServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $roles = realpath(__DIR__ . '/../config/roles.php');

        if ($this->app instanceof LaravelApplication) {
            $this->publishes([
                $roles => config_path('roles.php'),
            ]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('roles');
        }

        $this->mergeConfigFrom($roles, 'roles');
    }
}
