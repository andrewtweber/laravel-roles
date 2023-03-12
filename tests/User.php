<?php

namespace Roles\Tests;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Roles\HasPermissions;
use Roles\HasPermissionsInterface;
use Roles\HasRole;

/**
 * Class User
 *
 * @package Roles\Tests
 */
class User extends Authenticatable implements HasPermissionsInterface
{
    use HasPermissions, HasRole;
}
