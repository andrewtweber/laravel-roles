<?php

namespace Roles;

use Roles\Support\AllPermissions;
use Roles\Support\AnyPermission;
use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;
use Roles\Support\PermissionInterface;

/**
 * Trait HasPermissions
 *
 * @package Roles
 */
trait HasPermissions
{
    /**
     * @param Builder                         $query
     * @param PermissionInterface|string|null $permission
     */
    public function scopeWithPermission(Builder $query, PermissionInterface|string|null $permission)
    {
        if (! isset($permission)) {
            return;
        }

        $permission = $permission instanceof PermissionInterface ? $permission->value : $permission;

        $query->whereRaw('JSON_OVERLAPS(roles.permissions, \'["*", "' . $permission . '"]\')');
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

        return $this->getRole()?->permissions === [$permission];
    }

    /**
     * @param mixed $permission
     *
     * @return bool
     */
    public function hasPermission(mixed $permission): bool
    {
        // No role assigned
        if (! $this->getRole()) {
            return false;
        }

        // Super admin
        if (in_array('*', $this->getRole()->permissions)) {
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
        if (! $this->getRole()) {
            return false;
        }

        if (in_array('*', $this->getRole()->permissions)) {
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

        return count(array_intersect($permissions, $this->getRole()->permissions)) > 0;
    }

    /**
     * @return bool
     */
    public function hasNoPermissions(): bool
    {
        return ! $this->getRole()
            || $this->getRole()->permissions === null
            || $this->getRole()->permissions === [];
    }

    /**
     * @return bool
     */
    public function isSuper(): bool
    {
        return $this->getRole()?->permissions === ['*'];
    }
}
