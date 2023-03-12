<?php

namespace Roles\Support;

/**
 * Class AnyPermission
 *
 * @package Roles\Support
 */
class AnyPermission
{
    /**
     * AnyPermission constructor.
     *
     * @param array $permissions
     */
    public function __construct(
        public array $permissions
    ) {
    }
}
