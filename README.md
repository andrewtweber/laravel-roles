# Laravel Roles

[![CircleCI](https://dl.circleci.com/status-badge/img/gh/andrewtweber/laravel-roles/tree/master.svg?style=shield)](https://dl.circleci.com/status-badge/redirect/gh/andrewtweber/laravel-roles/tree/master)

## Installation

Install this package as a dependency using [Composer](https://getcomposer.org).

(This is not actually published yet, as it is in pre-alpha. I do not follow semantic versioning, so use at your own risk)


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

### Defining permissions

You should create a `Permission` enum backed by strings (see the one in the `tests` folder for an example) which
implements the `PermissionInterface` and uses the `PermissionTrait`.

You can define any permissions you like, including nested permissions:

```php
enum Permission: string implements PermissionInterface
{
    use PermissionTrait;
    
    case Admin = 'admin';
    
    case BanUser = 'users.ban';
    case DeleteUser = 'users.delete';
    
    // You don't have to define a wildcard permission but if you plan on checking for them,
    // it's recommended so that you can always check for an enum value.
    case Forums = 'forums.*';
    case LockForum = 'forums.lock';
    case DeleteThread = 'forums.threads.delete';
    case LockThread = 'forums.threads.lock';
}
```

After that you should define roles. The permissions can contain wildcards, but they need to be explicitly set as such.

```php
$role = new Role();
$role->permissions = ["*"]; // Superuser, will have all permissions
$role->permissions = ["admin"]; // Will only have Permission::Admin
$role->permissions = ["users.*"];  // Will have Permission::BanUser and Permission::DeleteUser
$role->permissions = ["forums.*"]; // Will have Permission::LockForum, Permission::DeleteThread, and Permission::LockThread
$role->permissions = ["forums.threads.*"]; // Will only have Permission::LockThread
$role->permissions = ["users"]; // Will have no permissions. Wildcards have to be set explicitly, to prevent mistakes
```

Here are some of the ways you can check for permissions:


```php
$role = new Role();
$role->hasNoPermissions(); // true

$role->permissions = ["*"];
$role->isSuper(); // true

$role->permissions = ["forums.*"];
$role->isSuper(); // false

// Checking for a single permission
$role->hasPermission('undefined'); // false
$role->hasPermission(Permission::Admin); // false
$role->onlyPermissionIs(Permission::LockForum); // false
$role->hasPermission(Permission::LockForum); // true
$role->hasPermission(Permission::LockThread); // true

// Checking for wildcard permissions
$role->hasPermission('forums'); // true
$role->hasPermission('forums.*'); // true
$role->hasPermission(Permission::Forums); // true
$role->hasPermission('forums.threads'); // true
$role->hasPermission('forums.threads.*'); // true

// Checking for multiple permissions
$role->hasPermission(Permission::all([Permission::BanUser, Permission::LockThread])); // false
$role->hasAllPermissions([Permission::BanUser, Permission::LockThread]); // false, Equivalent to previous line
$role->hasPermission(Permission::any([Permission::BanUser, Permission::LockThread])); // true
$role->hasAnyPermission([Permission::BanUser, Permission::LockThread]); // true, Equivalent to previous line
```

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
