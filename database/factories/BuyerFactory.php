<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Buyer;
use Faker\Generator as Faker;

$factory->define(Buyer::class, function (Faker $faker) {

    $status = $faker->randomElement(['active','inactive']);
    $sendPanda = $faker->randomElement([0,1]);
    $sendPlatDeclined = $faker->randomElement([0,1]);
    $sendPlat = $faker->randomElement([0,1]);
    $sendVertical = $faker->randomElement([0,1]);

    return [
        'buyer_key' => $faker->uuid,
        'status' => $status,
        'name' => $faker->streetName,
        'send_panda' => $sendPanda,
        'send_plat_declined' => $sendPlatDeclined,
        'send_plat' => $sendPlat,
        'send_vertical' => $sendVertical,
    ];
});
