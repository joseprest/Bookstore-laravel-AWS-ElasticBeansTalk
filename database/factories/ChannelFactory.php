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

$factory->define(Manivelle\Models\Channel::class, function ($faker) {
    return [
        //'type' => 'books',
        'handle' => $faker->slug
    ];
});

$channelTypes = Manivelle::channelTypes();
foreach ($channelTypes as $type) {
    $factory->defineAs(Manivelle\Models\Channel::class, $type, function ($faker) use ($type) {
        return [
            'type' => $type,
            'handle' => $faker->slug
        ];
    });
}
