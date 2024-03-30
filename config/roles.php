<?php

return [

    'default' => 'enum',

    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    |
    | The Permission provider. In the future will allow a Permission model,
    | for now only enums are supported. Your enum class must implement the
    | PermissionInterface interface.
    */
    'providers' => [
        'enum' => [
            'class' => \App\Support\Enums\Permission::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | If you need to extend the model classes you can do so and add your child
    | class here.
    */
    'models' => [
        'role' => \Roles\Models\Role::class,
        'user' => \App\Models\User::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Collections
    |--------------------------------------------------------------------------
    |
    | If you need to extend the collection classes you can do so and add your
    | child class here.
    */
    'collections' => [
        'role' => \Roles\Collections\RoleCollection::class,
    ],

];
