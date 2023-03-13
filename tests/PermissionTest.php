<?php

namespace Roles\Tests;

use Illuminate\Support\Facades\Config;
use Roles\Models\Role;
use InvalidArgumentException;

/**
 * Class PermissionTest
 *
 * @package Roles\Tests
 */
class PermissionTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        Config::set('roles.providers.enum.class', Permission::class);
        Config::set('roles.models.user', User::class);
    }

    /**
     * @test
     */
    public function permission_scope_works_on_roles()
    {
        $role1 = Role::factory()->state([
            'name' => 'Test 1',
            'permissions' => ['*'],
        ])->create();

        $role2 = Role::factory()->state([
            'name' => 'Test 2',
            'permissions' => [Permission::Shelter],
        ])->create();

        $role3 = Role::factory()->state([
            'name' => 'Test 3',
            'permissions' => [Permission::Shelter, Permission::Medical],
        ])->create();

        $role4 = Role::factory()->state([
            'name' => 'Test 4',
            'permissions' => [],
        ])->create();

        $roles = Role::where('name', 'LIKE', 'Test%')
            ->withPermission(Permission::Adoptions)
            ->get();
        $this->assertCount(1, $roles);
        $this->assertEquals($role1->id, $roles[0]->id);

        $roles = Role::where('name', 'LIKE', 'Test%')
            ->withPermission(Permission::Medical)
            ->get();
        $this->assertCount(2, $roles);
        $this->assertEqualsCanonicalizing([$role1->id, $role3->id], $roles->pluck('id')->all());

        $roles = Role::where('name', 'LIKE', 'Test%')
            ->withPermission(Permission::Shelter)
            ->get();
        $this->assertCount(3, $roles);
        $this->assertEqualsCanonicalizing([$role1->id, $role2->id, $role3->id], $roles->pluck('id')->all());
    }

    /**
     * @test
     */
    public function permission_scope_works_on_users()
    {
        $role1 = Role::factory()->state([
            'name' => 'Test 1',
            'permissions' => ['*'],
        ])->create();

        $role2 = Role::factory()->state([
            'name' => 'Test 2',
            'permissions' => [Permission::Shelter],
        ])->create();

        $role3 = Role::factory()->state([
            'name' => 'Test 3',
            'permissions' => [Permission::Shelter, Permission::Medical],
        ])->create();

        $role4 = Role::factory()->state([
            'name' => 'Test 4',
            'permissions' => [],
        ])->create();

        $user1 = User::factory()->for($role1)->state(['name' => 'Test User 1'])->create();
        $user2 = User::factory()->for($role2)->state(['name' => 'Test User 2'])->create();
        $user3 = User::factory()->for($role3)->state(['name' => 'Test User 3'])->create();
        $user4 = User::factory()->for($role4)->state(['name' => 'Test User 4'])->create();

        $users = User::where('name', 'LIKE', 'Test%')
            ->withPermission(Permission::Adoptions)
            ->get();
        $this->assertCount(1, $users);
        $this->assertEquals($user1->id, $users[0]->id);

        $users = User::where('name', 'LIKE', 'Test%')
            ->withPermission(Permission::Medical)
            ->get();
        $this->assertCount(2, $users);
        $this->assertEqualsCanonicalizing([$user1->id, $user3->id], $users->pluck('id')->all());

        $users = User::where('name', 'LIKE', 'Test%')
            ->withPermission(Permission::Shelter)
            ->get();
        $this->assertCount(3, $users);
        $this->assertEqualsCanonicalizing([$user1->id, $user2->id, $user3->id], $users->pluck('id')->all());
    }

    /**
     * @test
     */
    public function super_permissions_work()
    {
        $role = new Role();
        $role->permissions = ['*'];

        $this->assertTrue($role->isSuper());
        $this->assertTrue($role->hasPermission('*'));
        $this->assertFalse($role->onlyPermissionIs(Permission::Shelter));
        $this->assertTrue($role->hasPermission(Permission::Shelter));
        $this->assertTrue($role->hasPermission(Permission::Admin));
        $this->assertTrue($role->hasPermission(Permission::all([Permission::Shelter, Permission::Admin])));
        $this->assertTrue($role->hasAllPermissions([Permission::Shelter, Permission::Admin]));
        $this->assertTrue($role->hasPermission(Permission::any([Permission::Shelter, Permission::Admin])));
        $this->assertTrue($role->hasAnyPermission([Permission::Shelter, Permission::Admin]));
        $this->assertFalse($role->hasNoPermissions());
    }

    /**
     * @test
     */
    public function single_permissions_work()
    {
        $role = new Role();
        $role->permissions = [Permission::Shelter];

        $this->assertFalse($role->isSuper());
        $this->assertFalse($role->hasPermission('*'));
        $this->assertTrue($role->onlyPermissionIs(Permission::Shelter));
        $this->assertTrue($role->hasPermission(Permission::Shelter));
        $this->assertFalse($role->hasPermission(Permission::Admin));
        $this->assertFalse($role->hasPermission(Permission::all([Permission::Shelter, Permission::Admin])));
        $this->assertFalse($role->hasAllPermissions([Permission::Shelter, Permission::Admin]));
        $this->assertTrue($role->hasPermission(Permission::any([Permission::Shelter, Permission::Admin])));
        $this->assertTrue($role->hasAnyPermission([Permission::Shelter, Permission::Admin]));
        $this->assertFalse($role->hasNoPermissions());
    }

    /**
     * @test
     */
    public function multiple_permissions_work()
    {
        $role = new Role();
        $role->permissions = [Permission::Shelter, Permission::Admin];

        $this->assertFalse($role->isSuper());
        $this->assertFalse($role->hasPermission('*'));
        $this->assertFalse($role->onlyPermissionIs(Permission::Shelter));
        $this->assertTrue($role->hasPermission(Permission::Shelter));
        $this->assertTrue($role->hasPermission(Permission::Admin));
        $this->assertTrue($role->hasPermission(Permission::all([Permission::Shelter, Permission::Admin])));
        $this->assertTrue($role->hasAllPermissions([Permission::Shelter, Permission::Admin]));
        $this->assertTrue($role->hasPermission(Permission::any([Permission::Shelter, Permission::Admin])));
        $this->assertTrue($role->hasAnyPermission([Permission::Shelter, Permission::Admin]));
        $this->assertFalse($role->hasNoPermissions());
    }

    /**
     * @test
     */
    public function no_permissions_work()
    {
        $role = new Role();
        $role->permissions = [];

        $this->assertFalse($role->isSuper());
        $this->assertFalse($role->hasPermission('*'));
        $this->assertFalse($role->onlyPermissionIs(Permission::Shelter));
        $this->assertFalse($role->hasPermission(Permission::Shelter));
        $this->assertFalse($role->hasPermission(Permission::Admin));
        $this->assertFalse($role->hasPermission(Permission::all([Permission::Shelter, Permission::Admin])));
        $this->assertFalse($role->hasAllPermissions([Permission::Shelter, Permission::Admin]));
        $this->assertFalse($role->hasPermission(Permission::any([Permission::Shelter, Permission::Admin])));
        $this->assertFalse($role->hasAnyPermission([Permission::Shelter, Permission::Admin]));
        $this->assertTrue($role->hasNoPermissions());
    }

    /**
     * @test
     */
    public function wildcard_permissions_work()
    {
        // Permission is assigned without wildcard
        // It does not automatically inherit child permissions
        $role = new Role();
        $role->permissions = ['forums'];

        $this->assertTrue($role->hasPermission('forums'));
        $this->assertFalse($role->hasPermission('forums.*'));
        $this->assertFalse($role->hasPermission(Permission::Forums)); // equivalent to forums.*
        $this->assertFalse($role->hasPermission(Permission::LockForum));
        $this->assertFalse($role->hasPermission(Permission::PinForum));
        $this->assertFalse($role->hasPermission(Permission::ReplyForum));
        $this->assertFalse($role->hasPermission('forums.threads'));
        $this->assertFalse($role->hasPermission('forums.threads.*'));
        $this->assertFalse($role->hasPermission(Permission::Threads)); // equivalent to forums.threads.*
        $this->assertFalse($role->hasPermission(Permission::LockThread));
        $this->assertFalse($role->hasPermission(Permission::PinThread));
        $this->assertFalse($role->hasPermission(Permission::ReplyThread));
        $this->assertFalse($role->hasPermission('forums.users'));
        $this->assertFalse($role->hasPermission('forums.users.*'));
        $this->assertFalse($role->hasPermission(Permission::Users)); // equivalent to forums.users.*
        $this->assertFalse($role->hasPermission(Permission::BanUser));
        $this->assertFalse($role->hasPermission(Permission::BlockUser));

        // Permission is assigned with top level wildcard
        // It inherits all child and grandchild permissions
        $role = new Role();
        $role->permissions = [Permission::Forums]; // forums.*

        $this->assertTrue($role->hasPermission('forums'));
        $this->assertTrue($role->hasPermission('forums.*'));
        $this->assertTrue($role->hasPermission(Permission::Forums)); // equivalent to forums.*
        $this->assertTrue($role->hasPermission(Permission::LockForum));
        $this->assertTrue($role->hasPermission(Permission::PinForum));
        $this->assertTrue($role->hasPermission(Permission::ReplyForum));
        $this->assertTrue($role->hasPermission('forums.threads'));
        $this->assertTrue($role->hasPermission('forums.threads.*'));
        $this->assertTrue($role->hasPermission(Permission::Threads)); // equivalent to forums.threads.*
        $this->assertTrue($role->hasPermission(Permission::LockThread));
        $this->assertTrue($role->hasPermission(Permission::PinThread));
        $this->assertTrue($role->hasPermission(Permission::ReplyThread));
        $this->assertTrue($role->hasPermission('forums.users'));
        $this->assertTrue($role->hasPermission('forums.users.*'));
        $this->assertTrue($role->hasPermission(Permission::Users)); // equivalent to forums.users.*
        $this->assertTrue($role->hasPermission(Permission::BanUser));
        $this->assertTrue($role->hasPermission(Permission::BlockUser));

        // Permission is assigned with child wildcard
        // It inherits all grandchild permissions
        $role = new Role();
        $role->permissions = [Permission::Threads]; // forums.threads.*

        $this->assertFalse($role->hasPermission('forums'));
        $this->assertFalse($role->hasPermission('forums.*'));
        $this->assertFalse($role->hasPermission(Permission::Forums)); // equivalent to forums.*
        $this->assertFalse($role->hasPermission(Permission::LockForum));
        $this->assertFalse($role->hasPermission(Permission::PinForum));
        $this->assertFalse($role->hasPermission(Permission::ReplyForum));
        $this->assertTrue($role->hasPermission('forums.threads'));
        $this->assertTrue($role->hasPermission('forums.threads.*'));
        $this->assertTrue($role->hasPermission(Permission::Threads)); // equivalent to forums.threads.*
        $this->assertTrue($role->hasPermission(Permission::LockThread));
        $this->assertTrue($role->hasPermission(Permission::PinThread));
        $this->assertTrue($role->hasPermission(Permission::ReplyThread));
        $this->assertFalse($role->hasPermission('forums.users'));
        $this->assertFalse($role->hasPermission('forums.users.*'));
        $this->assertFalse($role->hasPermission(Permission::Users)); // equivalent to forums.users.*
        $this->assertFalse($role->hasPermission(Permission::BanUser));
        $this->assertFalse($role->hasPermission(Permission::BlockUser));

        // Permission is assigned with grandchild
        // It does not inherit anything
        $role = new Role();
        $role->permissions = [Permission::LockThread]; // forums.threads.lock

        $this->assertFalse($role->hasPermission('forums'));
        $this->assertFalse($role->hasPermission('forums.*'));
        $this->assertFalse($role->hasPermission(Permission::Forums)); // equivalent to forums.*
        $this->assertFalse($role->hasPermission(Permission::LockForum));
        $this->assertFalse($role->hasPermission(Permission::PinForum));
        $this->assertFalse($role->hasPermission(Permission::ReplyForum));
        $this->assertFalse($role->hasPermission('forums.threads'));
        $this->assertFalse($role->hasPermission('forums.threads.*'));
        $this->assertFalse($role->hasPermission(Permission::Threads)); // equivalent to forums.threads.*
        $this->assertTrue($role->hasPermission(Permission::LockThread));
        $this->assertFalse($role->hasPermission(Permission::PinThread));
        $this->assertFalse($role->hasPermission(Permission::ReplyThread));
        $this->assertFalse($role->hasPermission('forums.users'));
        $this->assertFalse($role->hasPermission('forums.users.*'));
        $this->assertFalse($role->hasPermission(Permission::Users)); // equivalent to forums.users.*
        $this->assertFalse($role->hasPermission(Permission::BanUser));
        $this->assertFalse($role->hasPermission(Permission::BlockUser));

        // Permission is assigned with multiple child wildcards
        // It inherits grandchild permissions for both children
        $role = new Role();
        $role->permissions = ['forums.threads.*', 'forums.users.*'];

        $this->assertFalse($role->hasPermission('forums'));
        $this->assertFalse($role->hasPermission('forums.*'));
        $this->assertFalse($role->hasPermission(Permission::Forums)); // equivalent to forums.*
        $this->assertFalse($role->hasPermission(Permission::LockForum));
        $this->assertFalse($role->hasPermission(Permission::PinForum));
        $this->assertFalse($role->hasPermission(Permission::ReplyForum));
        $this->assertTrue($role->hasPermission('forums.threads'));
        $this->assertTrue($role->hasPermission('forums.threads.*'));
        $this->assertTrue($role->hasPermission(Permission::Threads)); // equivalent to forums.threads.*
        $this->assertTrue($role->hasPermission(Permission::LockThread));
        $this->assertTrue($role->hasPermission(Permission::PinThread));
        $this->assertTrue($role->hasPermission(Permission::ReplyThread));
        $this->assertTrue($role->hasPermission('forums.users'));
        $this->assertTrue($role->hasPermission('forums.users.*'));
        $this->assertTrue($role->hasPermission(Permission::Users)); // equivalent to forums.users.*
        $this->assertTrue($role->hasPermission(Permission::BanUser));
        $this->assertTrue($role->hasPermission(Permission::BlockUser));
    }

    /**
     * @test
     */
    public function invalid_permissions_fail()
    {
        $role = new Role();
        $role->permissions = [Permission::Shelter];

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid permission type');

        $role->hasPermission(123);
    }
}
