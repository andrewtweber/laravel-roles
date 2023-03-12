<?php

namespace Roles\Support;

/**
 * Trait PermissionTrait
 *
 * @package Roles\Support
 */
trait PermissionTrait
{
    /**
     * @param array $permissions
     *
     * @return AllPermissions
     */
    public static function all(array $permissions): AllPermissions
    {
        return new AllPermissions($permissions);
    }

    /**
     * @param array $permissions
     *
     * @return AnyPermission
     */
    public static function any(array $permissions): AnyPermission
    {
        return new AnyPermission($permissions);
    }
}
