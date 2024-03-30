<?php

namespace Roles;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Roles\Collections\RoleCollection;
use Roles\Models\Role;
use Roles\Support\PermissionInterface;

/**
 * Trait HasManyRoles
 *
 * @package Roles
 *
 * @property RoleCollection<Role> $roles
 */
trait HasManyRoles
{
    public function getRoles(): RoleCollection
    {
        return $this->roles;
    }

    /**
     * @return BelongsToMany
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(config('roles.models.role'));
    }

    /**
     * @param Builder                         $query
     * @param PermissionInterface|string|null $permission
     */
    public function scopeWithPermission(Builder $query, PermissionInterface|string|null $permission)
    {
        if (! isset($permission)) {
            return;
        }

        $query->whereHas('roles', function ($roleQuery) use ($permission) {
            $roleQuery->withPermission($permission);
        });
    }
}
