<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(Manivelle\Models\Screen::class, function ($faker) {
    return [
        'auth_code' => rand(1,1000),
        'uuid' => $faker->uuid
    ];
});

$screenTypes = Manivelle::screenTypes();
foreach ($screenTypes as $type) {
    $factory->defineAs(Manivelle\Models\Screen::class, $type, function ($faker) use ($type) {
        return [

            'auth_code' => rand(1,1000),
            'uuid' => $faker->uuid,
            //'screentype' => $type
        ];
    });
}
