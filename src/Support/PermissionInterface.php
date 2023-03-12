<?php

namespace Roles\Support;

/**
 * Interface PermissionInterface
 *
 * @package Roles\Support
 */
interface PermissionInterface
{
    /**
     * @param array $permissions
     *
     * @return AllPermissions
     */
    public static function all(array $permissions): AllPermissions;

    /**
     * @param array $permissions
     *
     * @return AnyPermission
     */
    public static function any(array $permissions): AnyPermission;
}
