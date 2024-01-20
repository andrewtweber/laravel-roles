<?php

namespace Roles\Database\Factories;

use Roles\Models\Role;
use Illuminate\Database\Eloquent\Factories\Factory;
use Roles\Tests\Permission;

/**
 * Class RoleFactory
 *
 * @package Roles\Database\Factories
 */
class RoleFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Role::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'permissions' => [Permission::Shelter],
        ];
    }
}
