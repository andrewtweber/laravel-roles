<?php

namespace Roles;

use Illuminate\Database\Eloquent\Builder;
use Roles\Models\Role;
use Roles\Support\PermissionInterface;

/**
 * Interface HasPermissionsInterface
 *
 * @package Roles
 */
interface HasPermissionsInterface
{
    /**
     * @return Role|null
     */
    public function getRole(): ?Role;

    /**
     * @param Builder                         $query
     * @param PermissionInterface|string|null $permission
     */
    public function scopeWithPermission(Builder $query, PermissionInterface|string|null $permission);

    /**
     * @param PermissionInterface|string $permission
     *
     * @return bool
     */
    public function onlyPermissionIs(PermissionInterface|string $permission): bool;

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
