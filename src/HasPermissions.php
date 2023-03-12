<?php

namespace App\Models\Traits;

use App\Support\Enums\Permission;
use App\Support\Permissions\AllPermissions;
use App\Support\Permissions\AnyPermission;
use Illuminate\Database\Eloquent\Builder;
use InvalidArgumentException;

/**
 * Trait HasPermissions
 *
 * @package App\Models\Traits
 */
trait HasPermissions
{
    /**
     * @param Builder                $query
     * @param Permission|string|null $permission
     */
    public function scopeWithPermission(Builder $query, Permission|string|null $permission)
    {
        if (! isset($permission)) {
            return;
        }

        $permission = $permission instanceof Permission ? $permission->value : $permission;

        $query->whereRaw('JSON_OVERLAPS(roles.permissions, \'["*", "' . $permission . '"]\')');
    }

    /**
     * @param Permission|string $permission
     *
     * @return bool
     */
    public function onlyPermissionIs(mixed $permission): bool
    {
        if ($permission instanceof Permission) {
            $permission = $permission->value;
        }

        return $this->getRole()->permissions === [$permission];
    }

    /**
     * @param mixed $permission
     *
     * @return bool
     */
    public function hasPermission(mixed $permission): bool
    {
        // Super admin
        if (in_array('*', $this->getRole()->permissions)) {
            return true;
        }

        if ($permission instanceof Permission) {
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
     * @param Permission|string $permission
     *
     * @return bool
     */
    protected function hasSinglePermission(mixed $permission): bool
    {
        if (in_array('*', $this->getRole()->permissions)) {
            return true;
        }

        if ($permission instanceof Permission) {
            $permission = $permission->value;
        }

        return in_array($permission, $this->getRole()->permissions);
    }

    /**
     * @return bool
     */
    public function hasNoPermissions(): bool
    {
        return $this->getRole()->permissions === [];
    }

    /**
     * @return bool
     */
    public function isSuper(): bool
    {
        return $this->getRole()->permissions === ['*'];
    }
}
