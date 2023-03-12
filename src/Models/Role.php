<?php

namespace Roles\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Roles\HasPermissions;
use Roles\HasPermissionsInterface;

/**
 * Class Role
 *
 * @package Roles\Models
 *
 * @property int               $id
 * @property string            $name
 * @property array|string[]    $permissions
 * @property Carbon            $created_at
 * @property Carbon            $updated_at
 *
 * @property Collection|User[] users
 */
class Role extends Model implements HasPermissionsInterface
{
    use HasFactory, HasPermissions;

    protected $casts = [
        'permissions' => 'json',
    ];

    /**
     * @return Role
     */
    public function getRole(): Role
    {
        return $this;
    }

    /**
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(config('roles.models.user'));
    }
}
