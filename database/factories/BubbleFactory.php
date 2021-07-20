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

$factory->define(Manivelle\Models\Bubble::class, function ($faker) {
    return [
        'type' => 'bubble',
        'handle' => 'test'
    ];
});

$bubbleTypes = Manivelle::bubbleTypes();
foreach ($bubbleTypes as $type) {
    $factory->defineAs(Manivelle\Models\Bubble::class, $type, function ($faker) use ($type) {
        return [
            'type' => $type,
            'handle' => 'test'
        ];
    });
}
