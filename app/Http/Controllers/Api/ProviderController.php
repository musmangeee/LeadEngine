<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateProviderRequest;
use App\Http\Requests\Api\UpdateProviderRequest;
use App\Models\Provider;
use App\Models\ProviderDailyReport;
use DOMDocument;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;

class ProviderController extends Controller
{
    private $provider;

    public function __construct(Provider $provider){
        $this->provider = $provider;
    }

    public function index(Request $request){

        $providers = $this->provider->newQuery();
        $perPage = $request->has('perPage') ? (int) $request->perPage : 10;

        $results = $providers->paginate($perPage);
        return response()->json($results, 200);
    }

    public function create(CreateProviderRequest $request){
        $validated = $request->validated();
        $validated['provider_key'] =  Uuid::uuid4();

        $provider = $this->provider->create(array_except($validated,'deliverySchedules'));

        if($request->has('deliverySchedules') && !empty($request->get('deliverySchedules'))){
            foreach ($request->get('deliverySchedules') as $schedule) {
                $provider->deliverySchedules()->create(array_except($schedule,'id'));
            }
        }

        return response()->json([
            'data' => $provider
        ]);
    }

    public function update(UpdateProviderRequest $request, $id){
        $validated = $request->validated();
        $provider = $this->provider->findOrFail($id);

        $provider->update(array_except($validated,'deliverySchedules'));
        if($request->has('deliverySchedules') && !empty($request->get('deliverySchedules'))){
            $provider->deliverySchedules()->delete();
            foreach ($request->get('deliverySchedules') as $schedule) {
                $provider->deliverySchedules()->create(array_except($schedule,'id'));
            }
        }
        else {
            $provider->deliverySchedules()->delete();
        }

        return response()->json([
            'data' => $provider
        ]);
    }

    public function show($id){
        $detailProvider = $this->provider->with(['deliverySchedules'])->findOrFail($id);
        return response()->json([
            'data' => $detailProvider
        ]);
    }

    public function delete($id){
        $detailProvider = $this->provider->findOrFail($id);
        $detailProvider->deliverySchedules()->delete();
        $detailProvider->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function postInstruction($id){
        $provider = Provider::find($id);
        $post_data = '<?xml version="1.0"?><REQUEST><REFERRAL><PROVIDERUUID>'.$provider->provider_key.'</PROVIDERUUID><PORTFOLIOUUID>place_portfolio_uuid_here</PORTFOLIOUUID><REFURL>xyz.com</REFURL><TIERKEY/><IPADDRESS>107.77.201.114</IPADDRESS><AFFID>xyz1234</AFFID><SUBID>14488</SUBID><TEST>false</TEST></REFERRAL><CUSTOMER><PERSONAL><REQUESTEDAMOUNT>3500</REQUESTEDAMOUNT><SSN>999999999</SSN><DOB>1955-03-11</DOB><FIRSTNAME>Test</FIRSTNAME><LASTNAME>Test</LASTNAME><ADDRESS>2449 State Highway 22</ADDRESS><ADDRESS2/><CITY>Whitney</CITY><STATE>TX</STATE><ZIP>76692</ZIP><PHONE>(254)998-0269</PHONE><DLSTATE>TX</DLSTATE><DLNUMBER>16694315</DLNUMBER><CONTACTTIME>E</CONTACTTIME><ADDRESSMONTHS>0</ADDRESSMONTHS><ADDRESSYEARS>3</ADDRESSYEARS><RENTOROWN>R</RENTOROWN><ISMILITARY>false</ISMILITARY><ISCITIZEN>true</ISCITIZEN><OTHEROFFERS>false</OTHEROFFERS><EMAIL>xyz@gmail.com</EMAIL><LANGUAGE>en</LANGUAGE></PERSONAL><EMPLOYMENT><INCOMETYPE>E</INCOMETYPE><PAYTYPE>D</PAYTYPE><EMPMONTHS>0</EMPMONTHS><EMPYEARS>3</EMPYEARS><EMPNAME>Social security</EMPNAME><EMPPHONE>(254)583-6984</EMPPHONE><HIREDATE>2017-06-09</HIREDATE><EMPTYPE>F</EMPTYPE><JOBTITLE>Manager</JOBTITLE><PAYFREQUENCY>W</PAYFREQUENCY><NETMONTHLY>3000</NETMONTHLY><LASTPAYDATE>2020-06-08</LASTPAYDATE><NEXTPAYDATE>2020-07-08</NEXTPAYDATE><SECONDPAYDATE>2020-08-07</SECONDPAYDATE></EMPLOYMENT><BANK><BANKNAME>FIRST BANK TRUST</BANKNAME><ACCOUNTTYPE>C</ACCOUNTTYPE><ROUTINGNUMBER>111304051</ROUTINGNUMBER><ACCOUNTNUMBER>0001283866</ACCOUNTNUMBER><BANKMONTHS>0</BANKMONTHS><BANKYEARS>3</BANKYEARS></BANK><USERIPADDRESS>127.0.0.1</USERIPADDRESS><REFERENCES>abc</REFERENCES></CUSTOMER></REQUEST>';
        $responses['accepted'] = '<?xml version="1.0"?>
        <response>
            <status>accepted</status>
            <redirectUrl>https://dev.aimleadconnect.com/redirect/4893010/80e8a624-e15f-4a93-859c-a40209e6eab5</redirectUrl>
        </response>';
        $responses['rejected'] = '<?xml version="1.0"?>
        <response>
            <status>rejected</status>
            <message>denied_by_filter</message>
            <redirectUrl>https://dev.aimleadconnect.com/redirect/4893037/c9d431fd-4f21-4358-a279-f3368fae8a80</redirectUrl>
        </response>';
        $domxml = new DOMDocument('1.0');
        $domxml->preserveWhiteSpace = false;
        $domxml->formatOutput = true;
        $domxml->loadXML($post_data);
        $post_data = $domxml->saveXML();
        $domxml->loadXML($responses['accepted']);
        $responses['accepted'] = $domxml->saveXML();
        $domxml->loadXML($responses['rejected']);
        $responses['rejected'] = $domxml->saveXML();
        //$domxml->loadXML($responses['error']);
        //$responses['error'] = $domxml->saveXML();
        //$domxml->loadXML($responses['test']);
        //$responses['test'] = $domxml->saveXML();
        $pdf = App::make('dompdf.wrapper');
        $pdf->loadView('pdf.post-instruction-pdf', ['provider' => $provider, 'post_data' => $post_data, 'responses' => $responses]);
        return $pdf->stream();
        //return view('pdf.post-instruction-pdf', ['provider' => $provider, 'post_data' => $post_data]);
    }

    public function dailyStats(Request $request){
        $selectedDate = $request->get('selected_date');
        $providers = $this->provider->get();
        $providerStats = $providers->map(function($provider) use ($selectedDate){
            $totalLead = 0;
            $totalAccepted = 0;
            $totalRejected = 0;
            $totalError = 0;
            $totalRedirect = 0;
            $totalRedirectSuccess = 0;
            $totalRedirectError = 0;
            $totalRedirectDuration = 0;

            $providerDailyReport = ProviderDailyReport::where([
                'date' => Carbon::parse($selectedDate)->format('Y-m-d'),
                'provider_id' => $provider->id
            ])->first();
            if($providerDailyReport){
                $totalLead = $providerDailyReport->total_lead;
                $totalAccepted = $providerDailyReport->total_accepted;
                $totalRejected = $providerDailyReport->total_rejected;
                $totalError = $providerDailyReport->total_error;
                $totalRedirect = $providerDailyReport->total_redirect;
                $totalRedirectSuccess = $providerDailyReport->total_redirect_success;
                $totalRedirectError = $providerDailyReport->total_redirect_error;
                $totalRedirectDuration = $providerDailyReport->total_redirect_duration;
            }

            return [
                'name' => $provider->name,
                'total_lead' => $totalLead,
                'total_accepted' => $totalAccepted,
                'total_rejected' => $totalRejected,
                'total_error' => $totalError,
                'total_redirect' => $totalRedirect,
                'total_redirect_success' => $totalRedirectSuccess,
                'total_redirect_error' => $totalRedirectError,
                'total_redirect_duration' => $totalRedirectDuration
            ];
        });
        return response()->json([
            'data' => $providerStats
        ]);
    }

}
