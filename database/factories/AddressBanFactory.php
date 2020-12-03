<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\AddressBan;
use Faker\Generator as Faker;
use Illuminate\Support\Arr;

$factory->define(AddressBan::class, function (Faker $faker) {
    $type = Arr::shuffle([AddressBan::TYPE_HOSTNAME, AddressBan::TYPE_IP_ADDRESS])[0];

    $hostname = null;
    $ipAddress = null;
    if ($type == AddressBan::TYPE_HOSTNAME) {
        $hostname = $faker->domainName;
    } elseif ($type == AddressBan::TYPE_IP_ADDRESS) {
        $ipAddress = $faker->ipv4;
    }

    return [
        'hostname' => $hostname,
        'ip_address' => $ipAddress
    ];
});
