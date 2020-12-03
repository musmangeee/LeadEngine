<?php

use App\Models\Application;
use App\Models\ApplicationRedirect;
use App\Models\ApplicationStatus;
use App\Models\Provider;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class ApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        /*factory(App\Models\Application::class, 1)->create()->each(function ($application) {
            $application->status()->saveMany(factory(App\Models\ApplicationStatus::class, 1)->make());
        });*/

        //Create fake application

        $random_requested_amount = $faker->numberBetween(100, 2000);
        $requested_amount = round($random_requested_amount, -(strlen((string) $random_requested_amount)-2));

        $random_monthly_amount = $faker->numberBetween(500, 3000);
        $net_monthly_income = round($random_monthly_amount, -(strlen((string) $random_monthly_amount)-2));

        $address_months = $faker->numberBetween(0, 960);
        $emp_months = $faker->numberBetween(0, 860);

        $application = Application::create([
            'uuid'               => $faker->uuid,
            'provider_id'        => $faker->randomElement(Provider::pluck('id')),
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
            'enduser_ip_address' => $faker->ipv4,
            'incoming_server_ip_address' => $faker->ipv4,
            'is_fake'            => true
        ]);

        //Create fake application status

        $status = $faker->randomElement(['accepted', 'rejected']);

    if ($status == 'accepted') {
        $price_received = $faker->numberBetween(3, 97);
    } else {
        $price_received = 0;
    }

    if ($status == 'accepted') {
        $xml_response = "<response>
                <id>0</id>
                <status>accepted</status>
                <message></message>
                <price>$price_received</price>
                <redirect></redirect>
            </response>";
    }

        if ($status == 'rejected') {
            $xml_response = "<response>
                <id>0</id>
                <status>rejected</status>
                <message>$faker->realText(100)</message>
                <price></price>
                <redirect></redirect>
            </response>";
        }

        /* if ($status == 'error') {
            $xml_response = "<response>
                <id>0</id>
                <status>error</status>
                <message>$faker->realText(100)</message>
                <price></price>
                <redirect></redirect>
            </response>";
        } */

        $random_sec = rand(1,7);

        $application_status = ApplicationStatus::create([
            'application_id'      => $application->id,
            'xml_post_record'     => '',
            'xml_response_record' => $xml_response,
            'price_received'      => $price_received,
            'status'              => $status,
            'buyer' => 'vertical',
            'channel' => '10368',
            'time_used' => rand(1,30),
            'created_at' => Carbon::now()->addSecond($random_sec),
            'updated_at' => Carbon::now()->addSecond($random_sec)
        ]);

         //Create fake redirect

         $application_redirect = ApplicationRedirect::create([
            'application_id' => $application->id,
            'redirect_url' => $faker->url,
            'created_at' => Carbon::parse($application_status->created_at),
            'updated_at' => Carbon::parse($application_status->created_at)
        ]);

        $random_success_number = rand(1,100);
        $random_success_redirect_number = rand(1,100);
        $random_failed = 0;
        if($random_success_redirect_number <= 5){
            $random_failed = 1;
        }

        error_log($random_success_redirect_number);

        if($random_success_number > 5 && $status == 'accepted'){

            $application_redirect->redirect_url = $faker->url;
            $application_redirect->redirect_count = rand(0,3);
            $application_redirect->redirect_user_ip = $faker->ipv4;
            $application_redirect->user_agent = $faker->userAgent;
            $application_redirect->failed_reason = ($random_failed == 1) ? 'Randomly marked as failed by faker generarion.' : null;
            $application_redirect->redirected_at = Carbon::parse($application_status->created_at)->addSecond(rand(1,7));
            $application_redirect->save();
        }



    }
}
