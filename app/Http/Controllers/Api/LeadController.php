<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\EditLeadRequest;
use App\Http\Requests\CreateLeadRequest;
use App\Models\Application;
use App\Models\ApplicationRedirect;
use App\Models\ApplicationStatus;
use App\Models\FailedApplicationLog;
use App\Models\WidgetReport;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Spatie\ArrayToXml\ArrayToXml;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeadController extends Controller
{

    private $application;
    private $applicationRedirect;
    private $failedApplicationLog;
    private $widgetReport;

    public function __construct(Application $application, ApplicationRedirect $applicationRedirect, FailedApplicationLog $failedApplicationLog, WidgetReport $widgetReport)
    {
        $this->application = $application;
        $this->applicationRedirect = $applicationRedirect;
        $this->failedApplicationLog = $failedApplicationLog;
        $this->widgetReport = $widgetReport;
    }

    public function intake(CreateLeadRequest $request)
    {
        //get validated data from FormQuest CreateLeadRequest
        $data = $request->validated();

        //Inser application record
        $application = Application::create([
            'affid' => $data['REFERRAL']['AFFID'],
            'requested_amount' => $data['CUSTOMER']['PERSONAL']['REQUESTEDAMOUNT'],
            'email' => $data['CUSTOMER']['PERSONAL']['EMAIL'],
            'first_name' => $data['CUSTOMER']['PERSONAL']['FIRSTNAME'],
            'last_name' => $data['CUSTOMER']['PERSONAL']['LASTNAME'],
            'phone' => $data['CUSTOMER']['PERSONAL']['PHONE'],
            'address' => $data['CUSTOMER']['PERSONAL']['ADDRESS'],
            'city' => $data['CUSTOMER']['PERSONAL']['CITY'],
            'state' => $data['CUSTOMER']['PERSONAL']['STATE'],
            'zip' => $data['CUSTOMER']['PERSONAL']['ZIP'],
            'rent_or_own' => $data['CUSTOMER']['PERSONAL']['RENTOROWN'],
            'address_months' => $data['CUSTOMER']['PERSONAL']['ADDRESSMONTHS'],
            'address_years' => $data['CUSTOMER']['PERSONAL']['ADDRESSYEARS'],
            'dl_state' => $data['CUSTOMER']['PERSONAL']['DLSTATE'],
            'dl_number' => $data['CUSTOMER']['PERSONAL']['DLNUMBER'],
            'ssn' => $data['CUSTOMER']['PERSONAL']['SSN'],
            'is_military' => $data['CUSTOMER']['PERSONAL']['ISMILITARY'],
            'tcpa_optin' => $data['CUSTOMER']['PERSONAL']['TCPAOPTIN'],
            'income_type' => $data['CUSTOMER']['EMPLOYMENT']['INCOMETYPE'],
            'pay_frequency' => $data['CUSTOMER']['EMPLOYMENT']['PAYFREQUENCY'],
            'next_pay_date' => Carbon::createFromFormat('m-d-Y', $data['CUSTOMER']['EMPLOYMENT']['NEXTPAYDATE'])->format('Y-m-d'),
            'emp_name' => $data['CUSTOMER']['EMPLOYMENT']['EMPNAME'],
            'emp_phone' => $data['CUSTOMER']['EMPLOYMENT']['EMPPHONE'],
            'job_title' => $data['CUSTOMER']['EMPLOYMENT']['JOBTITLE'],
            'emp_months' => $data['CUSTOMER']['EMPLOYMENT']['EMPMONTHS'],
            'emp_years' => $data['CUSTOMER']['EMPLOYMENT']['EMPYEARS'],
            'pay_type' => $data['CUSTOMER']['EMPLOYMENT']['PAYTYPE'],
            'net_monthly_income' => $data['CUSTOMER']['BANK']['NETMONTHLYINCOME'],
            'bank_name' => $data['CUSTOMER']['BANK']['BANKNAME'],
            'account_number' => $data['CUSTOMER']['BANK']['ACCOUNTNUMBER'],
            'routing_number' => $data['CUSTOMER']['BANK']['ROUTINGNUMBER'],
            'mobile_device' => '',
            'user_agent' => $request->header('User-Agent'),
            'ip_address' => $request->getClientIp()
        ]);

        //building array to be convert to xml for posting
        $data_to_post = [
            'REFERRAL' => [
                'CHANNELID' => '',
                'PASSWORD' => '',
                'AFFSUBID' => $application->affid,
                'AFFSUBID2' => $application->affsubid2,
                'AFFSUBID3' => $application->affsubid3,
                'AFFSUBID4' => $application->affsubid4,
                'AFFSUBID5' => $application->affsubid5,
                'REFERRINGURL' => $application->ref_url,
                'MINPRICE' => '',
            ],
            'CUSTOMER' => [
                'PERSONAL' => [
                    'IPADDRESS' => $application->ip_address,
                    'REQUESTEDAMOUNT' => $application->requested_amount,
                    'SSN' => $application->ssn,
                    'DOB' => '', //not found in table
                    'FIRSTNAME' => $application->first_name,
                    'LASTNAME' => $application->last_name,
                    'ADDRESS' => $application->address,
                    'CITY' => $application->city,
                    'STATE' => $application->state,
                    'ZIP' => $application->zip,
                    'HOMEPHONE' => '', //not found in table
                    'CELLPHONE' => $application->phone,
                    'DLSTATE' => $application->dl_state,
                    'DLNUMBER' => $application->dl_number,
                    'ARMEDFORCES' => $application->is_military,
                    'CONTACTTIME' => '', //not found in table
                    'RENTOROWN' => $application->rent_or_own,
                    'EMAIL' => $application->email,
                    'ADDRESSMONTH' => $application->address_months,
                    'CITIZENSHIP' => '', //not found in table
                    'OWNCAR' => '', //not found in table
                    'TCPAOPTIN' => $application->tcpa_optin,
                    'USERAGENT' => $application->user_agent
                ],
                'EMPLOYMENT' => [
                    'INCOMETYPE' => $application->income_type,
                    'EMPTIME' => '', //not found in table
                    'EMPNAME' => $application->emp_name,
                    'EMPPHONE' => $application->emp_phone,
                    'JOBTITLE' => $application->job_title,
                    'PAYFREQUENCY' => $application->pay_frequency,
                    'NEXTPAYDATE' => $application->next_pay_date,
                    'SECONDPAYDATE' => '' //not found in table
                ],
                'BANK' => [
                    'BANKNAME' => $application->bank_name,
                    'BANKPHONE' => '', //not found in table
                    'ACCOUNTTYPE' => '', //not found in table
                    'ROUTINGNUMBER' => $application->routing_number,
                    'ACCOUNTNUMBER' => $application->account_number,
                    'BANKMONTHS' => '', //not found in table
                    'NETMONTHLYINCOME' => $application->net_monthly_income,
                    'DIRECTDEPOSIT' => ''
                ]
            ]
        ];

        //converting array to xml
        $xml_to_post = ArrayToXml::convert($data_to_post, 'REQUEST');

        //insert application status reccord
        ApplicationStatus::create([
            'application_id' => $application->id,
            'xml_post_record' => $xml_to_post,
        ]);

        //check if xml are in a proper format
        $xml2 = simplexml_load_string($xml_to_post) or die("Error: Cannot create object");

        //start posting to aimleadeintake
        $client = new Client();

        /* disable for testing
        $request = new Request('POST', env('AIMLEADINTAKE_POST_URL'), [ 'body'=>$xml_to_post]);
        $response = $client->send($request);

        $response_xml = simplexml_load_string($response->getBody()->getContents());
        $response_json = json_encode($response_xml);
        $response_data = json_decode($response_json, true);
        */

        $response_data['status'] = 'sold';
        $response_xml = 'test';

        if ($response_data['status'] == 'error') {
            return response()->json($response_xml, 240);
        }

        if ($response_data['status'] == 'sold') {
            return response()->json($response_xml, 445);
        }

        if ($response_data['status'] == 'reject') {
            return response()->json($response_xml, 446);
        }

        if ($response_data['status'] == '0') {
        
            return response()->json($response_xml, 446);
        }

        if ($response_data['status'] == 'test') {
            return response()->json($response_xml, 241);
        }
        
    }

    public function list(\Illuminate\Http\Request $request)
    {
        if ($request->has('sort')) {
            list($sortCol, $sortDesc) = explode('|', request()->sort);
            ($sortDesc == 'true') ? $sortDir = 'desc' : $sortDir = 'asc';
            $query = Application::orderBy($sortCol, $sortDir);
        } else {
            $query = Application::orderBy('id', 'asc');
        }

        if ($request->exists('filter')) {
            $query->where(function ($q) use ($request) {
                $value = "%{$request->filter}%";
                $q->where('first_name', 'like', $value)
                  ->orWhere('last_name', 'like', $value)
                  ->orWhere('email', 'like', $value)->orWhere('phone', 'like', $value);
            });
        }
        $perPage = $request->has('size') ? (int) $request->size : null;
        return response()->json($query->paginate($perPage), 200);
    }


    public function index(Request $request)
    {
        $current_date = $request->current_date;
        $applications = $this->application->with([
            'status',
            'provider'
        ])->newQuery();

        if($request->has('status') && !empty($request->get('status'))){
            $status = $request->get('status');
            $applications = $applications->whereHas('status',function($q) use ($status){
                $q->where('status',$status);
            });
        }


        if ($request->has('sortBy') && $request->has('sortDesc')) {
            if($request->get('sortDesc') == 'true'){
                $applications = $applications->orderBy($request->get('sortBy'), 'desc');
            }
            else {
                $applications = $applications->orderBy($request->get('sortBy'), 'asc');

            }
        }

        if ($request->has('filter') && !empty($request->get('filter'))){
            $applications->where(function ($q) use ($request) {
                $value = "%{$request->filter}%";
                $q->where('first_name', 'like', $value)
                  ->orWhere('last_name', 'like', $value)
                  ->orWhere('email', 'like', $value)->orWhere('phone', 'like', $value);
            });
        }

        if($request->has('startDate') && !empty($request->get('startDate'))){
            $applications = $applications->whereDate('created_at', '>=', Carbon::parse($request->get('startDate')));
        }

        if($request->has('endDate') && !empty($request->get('endDate'))){
            $applications = $applications->whereDate('created_at', '<=', Carbon::parse($request->get('endDate')));
        }

        $perPage = $request->has('perPage') ? (int) $request->perPage : 10;


        if($request->has('live') && $request->get('live') == 1){
/*
            $totalSold = $this->application->whereHas('status',function($q){
                $q->where('status',ApplicationStatus::STATUS_SOLD);
            })->count();
            $totalReject = $this->application->whereHas('status',function($q){
                $q->where('status',ApplicationStatus::STATUS_REJECT);
            })->count();
            $totalError = $this->application->whereHas('status',function($q){
                $q->where('status',ApplicationStatus::STATUS_ERROR);
            })->count();
            $results = collect([
                'total_sold' => $totalSold,
                'total_reject' => $totalReject,
                'total_error' => $totalError
            ]);
            $results = $results->merge($applications->paginate($perPage));*/



            $totalSold = $this->widgetReport->whereDate('created_at', '>=', Carbon::now()->startOfDay()->tz($request->timezone))->sum('total_accepted');
            
            $totalReject = $this->widgetReport->whereDate('created_at', '>=', Carbon::now()->startOfDay()->tz($request->timezone))->sum('total_rejected');

            $totalError = $this->widgetReport->whereDate('created_at', '>=', Carbon::now()->startOfDay()->tz($request->timezone))->sum('total_error');

            $results = collect([
                'total_sold' => $totalSold,
                'total_reject' => $totalReject,
                'total_error' => $totalError,
                'total_lead' => $totalSold + $totalReject + $totalError
            ]);

            $results = $results->merge($applications->paginate($perPage));

        }
        else{
            $results = $applications->paginate($perPage);
        }

        return response()->json($results, 200);
    }

    public function stats(Request $request){
        $data_today = $this->widgetReport
            ->where('date', '=', Carbon::parse($request->get('selectedDate')))
            ->first();
            

        if(!$data_today){
            return false;
        }

        //total stats
        /*$totalSold = $this->application
        ->where('created_at', '>=', Carbon::parse($request->get('selectedDate')) . ' 00:00:00')
        ->where('created_at', '<=', Carbon::parse($request->get('selectedDate')) . ' 23:59:59')
        ->whereHas('status',function($q){
            $q->where('status',ApplicationStatus::STATUS_SOLD);
        })->count();*/
        $totalSold = $data_today->total_accepted;




        /*$totalReject = $this->application
        ->where('created_at', '>=', Carbon::parse($request->get('selectedDate')) . ' 00:00:00')
        ->where('created_at', '<=', Carbon::parse($request->get('selectedDate')) . ' 23:59:59')
        ->whereHas('status',function($q){
            $q->where('status',ApplicationStatus::STATUS_REJECT);
        })->count();*/
        $totalReject = $data_today->total_rejected;

        /* $totalError = $this->application
        ->where('created_at', '>=', Carbon::parse($request->get('selectedDate')) . ' 00:00:00')
        ->where('created_at', '<=', Carbon::parse($request->get('selectedDate')) . ' 23:59:59')
        ->whereHas('status',function($q){
            $q->where('status',ApplicationStatus::STATUS_ERROR);
        })->count(); */
        $totalError = $data_today->total_error;






        //daily volume stats
        $startMonth = Carbon::parse($request->get('selectedDate'))->startOfMonth();
        $endMonth = Carbon::parse($request->get('selectedDate'))->endOfMonth();

        /*$monthRanges =  DB::table('applications')
            ->where('created_at','>=',$startMonth->format('Y-m-d H:i:s'))
            ->where('created_at','<=',$endMonth->format('Y-m-d H:i:s'))
            ->select(DB::raw('count(*) as count, DATE(created_at) as date'))
            ->groupBy('date')->get()->mapWithKeys(function($application){
                return [$application->date => $application->count];
            });*/

        $monthRanges =  DB::table('widget_reports')
            ->where('date','>=',$startMonth->format('Y-m-d'))
            ->where('date','<=',$endMonth->format('Y-m-d'))
            ->pluck('total_lead', 'date');

        /* $monthRangeAccepted =  DB::table('applications')
            ->join('application_statuses',function($join){
                $join->on('applications.id','=','application_statuses.application_id')
                ->where('application_statuses.status',ApplicationStatus::STATUS_SOLD);
            })
            ->where('application_statuses.created_at','>=',$startMonth->format('Y-m-d H:i:s'))
            ->where('application_statuses.created_at','<=',$endMonth->format('Y-m-d H:i:s'))
            ->select(DB::raw('count(*) as count, DATE(application_statuses.created_at) as date'))
            ->groupBy('date')->get()->mapWithKeys(function($application){
                return [$application->date => $application->count];
            }); */

        $monthRangeAccepted =  DB::table('widget_reports')
            ->where('date','>=',$startMonth->format('Y-m-d'))
            ->where('date','<=',$endMonth->format('Y-m-d'))
            ->pluck('total_accepted', 'date');


        /* $monthRangeRejected =  DB::table('applications')
            ->join('application_statuses',function($join){
                $join->on('applications.id','=','application_statuses.application_id')
                ->where('application_statuses.status',ApplicationStatus::STATUS_REJECT);
            })
            ->where('application_statuses.created_at','>=',$startMonth->format('Y-m-d H:i:s'))
            ->where('application_statuses.created_at','<=',$endMonth->format('Y-m-d H:i:s'))
            ->select(DB::raw('count(*) as count, DATE(application_statuses.created_at) as date'))
            ->groupBy('date')->get()->mapWithKeys(function($application){
                return [$application->date => $application->count];
            }); */

        $monthRangeRejected =  DB::table('widget_reports')
            ->where('date','>=',$startMonth->format('Y-m-d'))
            ->where('date','<=',$endMonth->format('Y-m-d'))
            ->pluck('total_rejected', 'date');

        /* $monthRangeError =  DB::table('applications')
            ->join('application_statuses',function($join){
                $join->on('applications.id','=','application_statuses.application_id')
                ->where('application_statuses.status',ApplicationStatus::STATUS_ERROR);
            })
            ->where('application_statuses.created_at','>=',$startMonth->format('Y-m-d H:i:s'))
            ->where('application_statuses.created_at','<=',$endMonth->format('Y-m-d H:i:s'))
            ->select(DB::raw('count(*) as count, DATE(application_statuses.created_at) as date'))
            ->groupBy('date')->get()->mapWithKeys(function($application){
                return [$application->date => $application->count];
            }); */

        $monthRangeError =  DB::table('widget_reports')
            ->where('date','>=',$startMonth->format('Y-m-d'))
            ->where('date','<=',$endMonth->format('Y-m-d'))
            ->pluck('total_error', 'date');


        $periods = CarbonPeriod::create($startMonth, $endMonth);

        $allRanges = [];
        $acceptedRanges = [];
        $rejectedRanges = [];
        $errorRanges = [];

        foreach ($periods as $date) {
            if(array_key_exists($date->format('Y-m-d'),$monthRanges->toArray())){
                $allRanges[$date->format('m/d')] = $monthRanges[$date->format('Y-m-d')];
            }
            else {
                $allRanges[$date->format('m/d')] = 0;
            }

            if(array_key_exists($date->format('Y-m-d'),$monthRangeAccepted->toArray())){
                $acceptedRanges[$date->format('m/d')] = $monthRangeAccepted[$date->format('Y-m-d')];
            }
            else {
                $acceptedRanges[$date->format('m/d')] = 0;
            }

            if(array_key_exists($date->format('Y-m-d'),$monthRangeRejected->toArray())){
                $rejectedRanges[$date->format('m/d')] = $monthRangeRejected[$date->format('Y-m-d')];
            }
            else {
                $rejectedRanges[$date->format('m/d')] = 0;
            }

            if(array_key_exists($date->format('Y-m-d'),$monthRangeError->toArray())){
                $errorRanges[$date->format('m/d')] = $monthRangeError[$date->format('Y-m-d')];
            }
            else {
                $errorRanges[$date->format('m/d')] = 0;
            }

        }


        //redirect stats
        /* $allRedirects = $this->applicationRedirect->whereDate('created_at', '=', Carbon::parse($request->get('selectedDate')))->count();
        $successRedirected = $this->applicationRedirect->whereDate('created_at', '=', Carbon::parse($request->get('selectedDate')))
            ->whereNotNull('redirected_at')->whereNull('failed_reason')->count();
        $notRedirected = $this->applicationRedirect->whereDate('created_at', '=', Carbon::parse($request->get('selectedDate')))
            ->whereNull('redirected_at')->count();
        $errorRedirected = $this->applicationRedirect->whereDate('created_at', '=', Carbon::parse($request->get('selectedDate')))
            ->whereNotNull('redirected_at')->whereNotNull('failed_reason')->count(); */

        $allRedirects = $data_today->total_redirect;
        $successRedirected = $data_today->total_redirect_success;
        $errorRedirected = $data_today->total_redirect_error;
        $notRedirected = $allRedirects - $successRedirected - $errorRedirected;



        //redirect time stats
        $totalTimeRedirected = $data_today->total_redirect_duration;

        //$totalTimeRedirected = $successRedirectedTime->sum('secondsDuration');
        if($successRedirected == 0){
            $avgTimeRedirected = 0;
        }
        else {
            $avgTimeRedirected = round($totalTimeRedirected / $successRedirected);
        }

        if($totalTimeRedirected == 0){
            $avgRedirectedMinute = 0;
            $avgRedirectedHour = 0;
        }
        else {
            $avgRedirectedMinute = round(60 / $totalTimeRedirected * $successRedirected, 2);
            $avgRedirectedHour = round(60 / $totalTimeRedirected * $successRedirected / 60, 2);
        }

        $results = collect([
            'all_redirected' => $allRedirects,
            'success_redirected' => $successRedirected,
            'avg_time_redirected' => $avgTimeRedirected,
            'total_time_redirected' => $totalTimeRedirected,
            'avg_time_redirected' => $avgTimeRedirected,
            'avg_redirected_minute' => $avgRedirectedMinute,
            'avg_redirected_hour' => $avgRedirectedHour,
            'not_redirected' => $notRedirected,
            'error_redirected' => $errorRedirected,
            'all_range' => $allRanges,
            'accepted_range' => $acceptedRanges,
            'rejected_range' => $rejectedRanges,
            'error_range' => $errorRanges,
            'total' => $totalSold + $totalReject + $totalError,
            'total_sold' => $totalSold,
            'total_reject' => $totalReject,
            'total_error' => $totalError
        ]);

        return response()->json([
            'data' => $results
        ]);
    }

    public function show($id){
        $application = $this->application->with(['status'])->findOrFail($id);
        return response()->json([
            'data' => $application
        ]);
    }

    public function update($id, EditLeadRequest $request){
        $validated = $request->validated();
        $application = $this->application->findOrFail($id);
        $application->update($validated);

        return response()->json([
            'data' => $application
        ]);
    }

    public function delete($id){
        $application = $this->application->findOrFail($id);
        $application->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }

    public function failedApplications(Request $request)
    {
        $failed_applications = $this->failedApplicationLog->newQuery();
        $perPage = $request->has('perPage') ? (int) $request->perPage : 10;

        $results = $failed_applications->paginate($perPage);
        return response()->json($results,200);
    }

    public function showFailedApplication($id){
        $failed_application = $this->failedApplicationLog->findOrFail($id);
        return response()->json([
            'data' => $failed_application
        ]);
    }

    public function postDetail($id){
        return ApplicationStatus::find($id);
    }

    public function dailyLead(Request $request){
        $result = WidgetReport::whereBetween('date', [$request->startDate, $request->endDate])->orderBy('date','desc')->get();
        return [
            'graph_data' => $result->pluck('total_lead', 'formatted_date'),
            'data' => $result
        ];
    }

}