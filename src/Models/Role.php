<?php

namespace Roles\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Roles\Collections\RoleCollection;
use Roles\HasPermissions;
use Roles\HasPermissionsInterface;
use Roles\Support\PermissionInterface;

/**
 * Class Role
 *
 * @package Roles\Models
 *
 * @property int              $id
 * @property string           $name
 * @property array<string>    $permissions
 * @property Carbon           $created_at
 * @property Carbon           $updated_at
 *
 * @property Collection<User> $users
 */
class Role extends Model implements HasPermissionsInterface
{
    use HasFactory, HasPermissions;

    protected $casts = [
        'permissions' => 'json',
    ];

    public function newCollection(array $models = [])
    {
        $class = config('roles.collections.role');

        return new $class($models);
    }

    /**
     * @return RoleCollection<Role>
     */
    public function getRoles(): RoleCollection
    {
        $class = config('roles.collections.role');

        return new $class([$this]);
    }

    /**
     * @return BelongsToMany|HasMany
     */
    public function users(): BelongsToMany|HasMany
    {
        return $this->hasMany(config('roles.models.user'));
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

        $permission = $permission instanceof PermissionInterface ? $permission->value : $permission;

        $query->whereRaw('JSON_OVERLAPS(roles.permissions, \'["*", "' . $permission . '"]\')');
    }
}
