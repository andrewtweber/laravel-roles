<?php

namespace Roles;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Roles\Collections\RoleCollection;
use Roles\Models\Role;
use Roles\Support\PermissionInterface;

/**
 * Trait HasRole
 *
 * @package Roles
 *
 * @property ?int  $role_id
 * @property ?Role $role
 */
trait HasRole
{
    /**
     * @return RoleCollection
     */
    public function getRoles(): RoleCollection
    {
        $class = config('roles.collections.role');

        return new $class([$this->role]);
    }

    /**
     * @return BelongsTo
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(config('roles.models.role'));
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

        $query->whereHas('role', function ($roleQuery) use ($permission) {
            $roleQuery->withPermission($permission);
        });
    }
}
