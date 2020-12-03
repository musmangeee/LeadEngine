<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(\App\Models\Redirect::class, function (Faker $faker) {
    $status = $faker->randomElement(['sold', 'reject', 'error']);

    if ($status == 'sold') {
        $price_received = $faker->numberBetween(1, 50);
    } else {
        $price_received = 0;
    }

    if ($status == 'sold') {
        $xml_response = "<response>
    <id>0</id>
    <status>sold</status>
    <message></message>
    <price>$price_received</price>
    <redirect></redirect>
</response>";
    }

    if ($status == 'reject') {
        $xml_response = "<response>
    <id>0</id>
    <status>reject</status>
    <message>$faker->realText(100)</message>
    <price></price>
    <redirect></redirect>
</response>";
    }

    if ($status == 'error') {
        $xml_response = "<response>
    <id>0</id>
    <status>error</status>
    <message>$faker->realText(100)</message>
    <price></price>
    <redirect></redirect>
</response>";
    }

    return [
        'xml_post_record'     => '',
        'xml_response_record' => $xml_response,
        'price_received'      => $price_received,
        'status'              => $status,
    ];
});
