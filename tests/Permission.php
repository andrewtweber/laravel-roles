<?php

namespace Roles\Tests;

use Roles\Support\PermissionInterface;
use Roles\Support\PermissionTrait;

/**
 * Enum Permission
 *
 * @package Roles\Tests
 */
enum Permission: string implements PermissionInterface
{
    use PermissionTrait;

    case Admin = 'admin';
    case Adoptions = 'adoptions';
    case Shelter = 'shelter';
    case Medical = 'medical';
}
