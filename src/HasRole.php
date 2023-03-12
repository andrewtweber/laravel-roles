<?php

namespace Roles;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Roles\Models\Role;

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
}
