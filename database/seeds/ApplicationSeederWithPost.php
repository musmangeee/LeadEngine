<?php

use App\Models\Application;
use App\Models\ApplicationRedirect;
use App\Models\ApplicationStatus;
use App\Models\Integration;
use App\Models\Portfolio;
use App\Models\PortfolioRouting;
use App\Models\Provider;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;
use GuzzleHttp\Client;

class ApplicationSeederWithPost extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    private $validate_income_type;
    private $validate_pay_frequency;
    private $employee_type;

    public function __construct()
    {
        $this->validate_income_type = ['E', 'D', 'S', 'U', 'P', 'O']; // Employed, Disability, Social Security, Unemployed, Pension, Others

        $this->validate_pay_frequency = ['W', 'B', 'S']; // Weekly, Bi-weekly, Semi-monthly

        $this->employee_type = ['F', 'P']; //Fulltime, Parttime
    }

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
            'provider_id'        => $faker->randomElement(Provider::pluck('id')),
            'portfolio_id'       => $faker->randomElement(Portfolio::where('status','active')->has('routings')->pluck('id')),
            'ref_url'            => $faker->url,
            'affid'              => $faker->numberBetween(1, 64),
            'requested_amount'   => $requested_amount,
            'email'              => $faker->email,
            'first_name'         => '$faker->firstName',
            'last_name'          => $faker->lastName,
            'dob'                => $faker->dateTimeThisCentury->format('Y-m-d'),
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
            //'tcpa_optin'         => $faker->randomElement(['Yes', 'No']),
            'income_type'        => $faker->randomElement($this->validate_income_type),
            'pay_frequency'      => $faker->randomElement($this->validate_pay_frequency),
            'next_pay_date'      => $faker->dateTimeBetween('now', '+1 week', 'UTC'),
            'emp_name'           => $faker->company,
            'hire_date'          => $faker->dateTimeBetween('-30 years', 'now', 'UTC'),
            'emp_type'           => $faker->randomElement($this->employee_type),
            'last_pay_date'      => $faker->dateTimeBetween('+1 week', '+2 week', 'UTC'),
            'second_pay_date'    => $faker->dateTimeBetween('+2 week', '+3 week', 'UTC'),
            'emp_phone'          => $faker->phoneNumber,
            'job_title'          => $faker->jobTitle,
            'emp_months'         => $emp_months,
            'emp_years'          => floor($emp_months),
            'pay_type'           => $faker->randomElement(['cash', 'check', 'transfer']),
            'net_monthly_income' => $net_monthly_income,
            'bank_name'          => $faker->randomElement(['Chase', 'BBVA', 'Huntington', 'M&T', 'Woodforest National', 'Sun', 'Region', 'HSBC', 'Bancorp', 'Iberiabank', 'Great Western', 'Frost']),
            'account_type'       => $faker->randomElement(['saving', 'current']),
            'bank_months'        => $faker->numberBetween(3, 960),
            'bank_years'         => $faker->numberBetween(1, 80),
            'account_number'     => $faker->bankAccountNumber,
            'routing_number'     => $faker->randomNumber(9),
            'mobile_device'      => '',
            'user_agent'         => $faker->userAgent,
            'enduser_ip_address' => $faker->ipv4,
            'incoming_server_ip_address' => $faker->ipv4,
            'is_fake'            => true
        ]);

        $portfolio = Portfolio::find($application->portfolio_id);
        $portfolio_routings = $portfolio->routings()->whereHasMorph('routeable', '*', function($q) {
            $q->where('status','active');
        })
        ->pluck('traffic_percentage', 'id')->toArray();
        $portfolio_routing_id = $this->getRandomWeightedElement($portfolio_routings);
        $portfolio_routing = PortfolioRouting::find($portfolio_routing_id);

        $integration = Integration::where('channel', 'vertical')->first();




        $applications_columns = Schema::getColumnListing('applications');
        $xml = $integration->post_body;

        foreach($applications_columns as $column){
            print_r($column);
                $xml = str_replace("[{$column}]", $application[$column], $xml);
        }

        $application_status = ApplicationStatus::create([
            'portfolio_routing_id' => $portfolio_routing->id,
            'application_id' => $application->id,
            'xml_post_record' => $xml,
            'buyer' => 'vertical',
            'channel' => $portfolio_routing->routeable->channel_id
        ]);

        $time_start = microtime(true);

        $client = new Client();
        $response = $client->request('POST', "{$integration->post_url}/{$portfolio_routing->routeable->channel_id}?waf={$portfolio_routing->routeable->waf_key}", [
              'headers' => [
                 'Content-Type' => 'text/xml'
               ],
              'body'   => $xml
            ]);

        $posting_time_used = microtime(true) - $time_start;

        $response_xml = simplexml_load_string($response->getBody()->getContents());
        $response_xml_string = $response->getBody();

        $response_json = json_encode($response_xml);
        $response_data = json_decode($response_json, true);

        $application_status->xml_response_record = $response->getBody();
        $application_status->save();

        $response_array = [];

        if ($response_data['Result'] == 'R') {
            $redirect_url = $response_data['DeniedPageRedirectUrl'];
            $response_array['status'] = 'rejected';
            $response_array['message'] = $response_xml->RejectionReason;
            $response_array['redirectUrl'] = env('LEAD_CONNECT_URL') . "/redirect/{$application->id}/{$application->uuid}";
        }

        if ($response_data['Result'] == 'A') {
            $redirect_url = $response_data['RedirectUrl'];
            $response_array['status'] = 'accepted';
            $response_array['redirectUrl'] = env('LEAD_CONNECT_URL') . "/redirect/{$application->id}/{$application->uuid}";
        }


        $application_status->status = $response_array['status'];
        $application_status->time_used = $posting_time_used;
        $application_status->save();

        ApplicationRedirect::create([
            'application_id' => $application->id,
            'redirect_url' => $redirect_url,
            'redirect_count' => 0,
        ]);

    }

    function getRandomWeightedElement(array $weightedValues) {
        $rand = mt_rand(1, (int) array_sum($weightedValues));

        foreach ($weightedValues as $key => $value) {
          $rand -= $value;
          if ($rand <= 0) {
            return $key;
          }
        }
      }
}
