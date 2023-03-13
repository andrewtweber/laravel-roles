<?php

namespace Roles;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Roles\Models\Role;
use Roles\Support\PermissionInterface;

/**
 * Trait HasRole
 *
 * @package Roles
 *
 * @property int|null  $role_id
 * @property Role|null role
 */
trait HasRole
{
    /**
     * @return Role|null
     */
    public function getRole(): ?Role
    {
        return $this->role;
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
