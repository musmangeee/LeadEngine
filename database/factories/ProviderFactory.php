<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Provider;
use Faker\Generator as Faker;

$factory->define(Provider::class, function (Faker $faker) {

    $status = $faker->randomElement(['active','inactive']);

    return [
        'provider_key' => $faker->uuid,
        'status' => $status,
        'name' => $faker->streetName,
        'description' => $faker->sentences(3, true)
    ];
});
