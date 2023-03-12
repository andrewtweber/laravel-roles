# Laravel Roles

CircleCI here

## Installation

Install this package as a dependency using [Composer](https://getcomposer.org).

``` bash
composer require andrewtweber/laravel-roles
```

If you want to change the config, run:

```
php artisan vendor:publish --provider="Roles\SnaccsServiceProvider"
```

This will publish the file `config/roles.php`.

## Usage

Update your User model to implement the `HasPermissionsInterface` and use the `HasPermissions` trait.

Also add the `BelongsTo` relationship to the `Role` model.
