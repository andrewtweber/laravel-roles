# Laravel Roles

CircleCI here

## Installation

Install this package as a dependency using [Composer](https://getcomposer.org).

``` bash
composer require andrewtweber/laravel-roles
```

If you want to change the config, run:

```
php artisan vendor:publish --provider="Roles\RolesServiceProvider"
```

This will publish the file `config/roles.php`.

## Usage

Update your User model to implement the `HasPermissionsInterface` and use the `HasPermissions` and `HasRole` traits.

To create a "Super User" role, just give it all permissions: `["*"]`

### Middleware

You can register the middleware in your Http Kernel:

```php
protected $routeMiddleware = [
    'permission' => \Roles\Http\Middleware\Permission::class,
];
```

Then you can guard routes with `Route::middleware('permission:{name}')`. This only works when checking
for a single permission though, if you need to check for multiple permissions, you'll have to add it to the
controller's constructor:

```php
class MyController extends Controller
{
    public function __construct() {
        $this->middleware();
    }
}
```

### Laravel Nova

If you have Laravel Nova, copy the `src/Nova/*.stub` files into your Nova models directory and modify as necessary.

## Testing

```
phpunit
```

## TODO

* Permissions scope tests currently not passing
* Support a `Permission` model instead of just enum
* Configure a default role
