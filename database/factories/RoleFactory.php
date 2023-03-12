<?php

namespace Roles\Database\Factories;

use Roles\Models\Role;
use App\Support\Enums\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * Class RoleFactory
 *
 * @package Database\Factories
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
