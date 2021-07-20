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

$factory->defineAs(Folklore\EloquentMediatheque\Models\Metadata::class, 'string', function ($faker) {
    return [
        'type' => 'string',
        'value' => $faker->name()
    ];
});

$factory->defineAs(Folklore\EloquentMediatheque\Models\Metadata::class, 'integer', function ($faker) {
    return [
        'type' => 'integer',
        'value_integer' => $faker->randomDigit()
    ];
});
