<?php

namespace Roles\Tests;

use Roles\Support\PermissionInterface;
use Roles\Support\PermissionTrait;

/**
 * Enum Permission
 *
 * @package Roles\Tests
 */
enum Permission: string implements PermissionInterface
{
    use PermissionTrait;

    case Admin = 'admin';
    case Adoptions = 'adoptions';
    case Shelter = 'shelter';
    case Medical = 'medical';

    // Nested 1 level
    case Forums = 'forums.*';
    case LockForum = 'forums.lock';
    case PinForum = 'forums.pin';
    case ReplyForum = 'forums.reply';

    // Nested 2 levels
    case Threads = 'forums.threads.*';
    case LockThread = 'forums.threads.lock';
    case PinThread = 'forums.threads.pin';
    case ReplyThread = 'forums.threads.reply';

    case Users = 'forums.users.*';
    case BanUser = 'forums.users.ban';
    case BlockUser = 'forums.users.block';
}
