<?php

namespace Roles\Collections;

use Illuminate\Database\Eloquent\Collection;
use InvalidArgumentException;
use Roles\HasPermissionsInterface;
use Roles\Support\AllPermissions;
use Roles\Support\AnyPermission;
use Roles\Support\PermissionInterface;

/**
 * Class RoleCollection
 *
 * @package Roles\Collections
 */
class RoleCollection extends Collection
{
    /**
     * @return array<string>
     */
    public function permissions(): array
    {
        return $this->pluck('permissions')
            ->flatten()
            ->unique()
            ->values()
            ->toArray();
    }

    /**
     * @param PermissionInterface|string $permission
     *
     * @return bool
     */
    public function onlyPermissionIs(PermissionInterface|string $permission): bool
    {
        if ($permission instanceof PermissionInterface) {
            $permission = $permission->value;
        }

        return $this->permissions() === [$permission];
    }

    /**
     * @param mixed $permission
     *
     * @return bool
     */
    public function hasPermission(mixed $permission): bool
    {
        // No role assigned
        if ($this->isEmpty()) {
            return false;
        }

        // Super admin
        if ($this->isSuper()) {
            return true;
        }

        if ($permission instanceof PermissionInterface) {
            $permission = $permission->value;
        }

        // Single permission
        if (is_string($permission)) {
            return $this->hasSinglePermission($permission);
        }

        // Must have all permissions listed
        if ($permission instanceof AllPermissions) {
            return $this->hasAllPermissions($permission->permissions);
        }

        // May have any permission listed
        if ($permission instanceof AnyPermission) {
            return $this->hasAnyPermission($permission->permissions);
        }

        throw new InvalidArgumentException("Invalid permission type");
    }

    /**
     * @param array $permissions
     *
     * @return bool
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (! $this->hasSinglePermission($permission)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param array $permissions
     *
     * @return bool
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasSinglePermission($permission)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param PermissionInterface|string $permission
     *
     * @return bool
     */
    protected function hasSinglePermission(PermissionInterface|string $permission): bool
    {
        // No role assigned
        if ($this->isEmpty()) {
            return false;
        }

        // Super admin
        if ($this->isSuper()) {
            return true;
        }

        if ($permission instanceof PermissionInterface) {
            $permission = $permission->value;
        }

        $permissions = [$permission];
        $parts = explode('.', $permission);

        if (last($parts) === '*') {
            $permissions = [];
            array_pop($parts);
        }
        $permission = '';

        for ($i = 0; $i < count($parts); $i++) {
            $permission .= $parts[$i] . '.';

            $permissions[] = $permission . '*';
        }

        return count(array_intersect($permissions, $this->permissions())) > 0;
    }

    /**
     * @return bool
     */
    public function hasNoPermissions(): bool
    {
        return $this->isEmpty()
            || $this->permissions() === null
            || $this->permissions() === [];
    }

    /**
     * @return bool
     */
    public function isSuper(): bool
    {
        return in_array('*', $this->permissions());
    }
}
