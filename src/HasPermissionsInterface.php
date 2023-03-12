<?php

namespace Roles;

use Illuminate\Database\Eloquent\Builder;

/**
 * Interface HasPermissionsInterface
 *
 * @package Roles
 */
interface HasPermissionsInterface
{
    /**
     * @param Builder                $query
     * @param Permission|string|null $permission
     */
    public function scopeWithPermission(Builder $query, Permission|string|null $permission);

    /**
     * @param Permission|string $permission
     *
     * @return bool
     */
    public function onlyPermissionIs(mixed $permission): bool;

    /**
     * @param mixed $permission
     *
     * @return bool
     */
    public function hasPermission(mixed $permission): bool;

    /**
     * @param array $permissions
     *
     * @return bool
     */
    public function hasAllPermissions(array $permissions): bool;

    /**
     * @param array $permissions
     *
     * @return bool
     */
    public function hasAnyPermission(array $permissions): bool;

    /**
     * @return bool
     */
    public function hasNoPermissions(): bool;

    /**
     * @return bool
     */
    public function isSuper(): bool;
}
