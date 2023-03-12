<?php

namespace Roles\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Http\Request;
use Roles\HasPermissionsInterface;

/**
 * Class Permission
 *
 * @package Roles\Http\Middleware
 */
class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string  $permission
     * @param string  $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $permission, $guard = null)
    {
        /** @var HasPermissionsInterface $user */
        $user = Auth::guard($guard)->user();

        if (! $user) {
            abort(401);
        }

        if ($permission && ! $user->hasPermission($permission)) {
            abort(403);
        }

        return $next($request);
    }
}
