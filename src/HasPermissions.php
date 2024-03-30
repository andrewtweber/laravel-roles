<?php

namespace Roles;

use Roles\Support\PermissionInterface;

/**
 * Trait HasPermissions
 *
 * @package Roles
 */
trait HasPermissions
{
    /**
     * @param PermissionInterface|string $permission
     *
     * @return bool
     */
    public function onlyPermissionIs(PermissionInterface|string $permission): bool
    {
        return $this->getRoles()->onlyPermissionIs($permission);
    }

    /**
     * @param mixed $permission
     *
     * @return bool
     */
    public function hasPermission(mixed $permission): bool
    {
        return $this->getRoles()->hasPermission($permission);
    }

    /**
     * @param array $permissions
     *
     * @return bool
     */
    public function hasAllPermissions(array $permissions): bool
    {
        return $this->getRoles()->hasAllPermissions($permissions);
    }

    /**
     * @param array $permissions
     *
     * @return bool
     */
    public function hasAnyPermission(array $permissions): bool
    {
        return $this->getRoles()->hasAnyPermission($permissions);
    }

    /**
     * @param PermissionInterface|string $permission
     *
     * @return bool
     */
    protected function hasSinglePermission(PermissionInterface|string $permission): bool
    {
        return $this->getRoles()->hasSinglePermission($permission);
    }

    /**
     * @return bool
     */
    public function hasNoPermissions(): bool
    {
        return $this->getRoles()->hasNoPermissions();
    }

    /**
     * @return bool
     */
    public function isSuper(): bool
    {
        return $this->getRoles()->isSuper();
    }
}
