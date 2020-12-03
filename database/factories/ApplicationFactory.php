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

$factory->define(\App\Models\Application::class, function (Faker $faker) {
    $random_requested_amount = $faker->numberBetween(100, 2000);
    $requested_amount = round($random_requested_amount, -(strlen((string) $random_requested_amount)-2));

    $random_monthly_amount = $faker->numberBetween(500, 3000);
    $net_monthly_income = round($random_monthly_amount, -(strlen((string) $random_monthly_amount)-2));

    $address_months = $faker->numberBetween(0, 960);
    $emp_months = $faker->numberBetween(0, 860);

    return [
        'affid'              => $faker->numberBetween(1, 64),
        'requested_amount'   => $requested_amount,
        'email'              => $faker->email,
        'first_name'         => $faker->firstName,
        'last_name'          => $faker->lastName,
        'phone'              => $faker->phoneNumber,
        'address'            => $faker->address,
        'city'               => $faker->city,
        'state'              => \Faker\Provider\en_US\Address::stateAbbr(),
        'zip'                => $faker->postcode,
        'rent_or_own'        => $faker->randomElement(['rent', 'own']),
        'address_months'     => $address_months,
        'address_years'      => floor($address_months),
        'dl_state'           => \Faker\Provider\en_US\Address::stateAbbr(),
        'dl_number'          => $faker->randomNumber(9),
        'ssn'                => $faker->randomNumber(9),
        'is_military'        => $faker->randomElement(['Yes', 'No']),
        'tcpa_optin'         => $faker->randomElement(['Yes', 'No']),
        'income_type'        => $faker->randomElement(['Job Income', 'Self Employed', 'Benefits', 'Pension', 'Social Security', 'Disability', 'Income', 'Military', 'Other']),
        'pay_frequency'      => $faker->randomElement(['Weekly', 'Every 2 Weeks', 'Twice A Month', 'Monthly']),
        'next_pay_date'      => $faker->dateTimeBetween('now', '+1 week', 'UTC'),
        'emp_name'           => $faker->company,
        'emp_phone'          => $faker->phoneNumber,
        'job_title'          => $faker->jobTitle,
        'emp_months'         => $emp_months,
        'emp_years'          => floor($emp_months),
        'pay_type'           => $faker->randomElement(['cash', 'check', 'transfer']),
        'net_monthly_income' => $net_monthly_income,
        'bank_name'          => $faker->randomElement(['Chase', 'BBVA', 'Huntington', 'M&T', 'Woodforest National', 'Sun', 'Region', 'HSBC', 'Bancorp', 'Iberiabank', 'Great Western', 'Frost']),
        'account_number'     => $faker->bankAccountNumber,
        'routing_number'     => $faker->randomNumber(9),
        'mobile_device'      => '',
        'user_agent'         => $faker->userAgent,
        'ip_address'         => $faker->ipv4,
        'is_fake'            => true
    ];
});
