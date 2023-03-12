<?php

namespace Roles;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

use Illuminate\Foundation\Application as LaravelApplication;

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
        $formatting = realpath(__DIR__ . '/../../config/formatting.php');
        $system = realpath(__DIR__ . '/../../config/system.php');
        $views = realpath(__DIR__ . '/../../resources/views');

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
