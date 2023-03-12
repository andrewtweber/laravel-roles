<?php

namespace Roles\Support;

/**
 * Class AllPermissions
 *
 * @package Roles\Support
 */
class AllPermissions
{
    /**
     * AllPermissions constructor.
     *
     * @param array $permissions
     */
    public function __construct(
        public array $permissions
    ) {
    }
}
