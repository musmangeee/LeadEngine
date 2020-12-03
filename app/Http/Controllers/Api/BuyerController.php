<?php

namespace App\Http\Controllers\Api;

use App\Models\Buyer;
use Ramsey\Uuid\Uuid;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CreateBuyerRequest;
use App\Http\Requests\Api\CreateCountByBuyerReportRequest;
use App\Http\Requests\Api\UpdateBuyerRequest;
use App\Mail\CountByBuyerReport;
use App\Models\BuyerChannelDailyReport;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class BuyerController extends Controller
{
    private $buyer;

    public function __construct(Buyer $buyer){
        $this->buyer = $buyer;
    }

    public function index(Request $request){
        $buyers = $this->buyer->with(['deliverySchedules']);
        $perPage = $request->has('perPage') ? (int) $request->perPage : 10;

        $results = $buyers->paginate($perPage);
        return response()->json($results,200);
    }

    public function create(CreateBuyerRequest $request){
        $validated = $request->validated();
        $validated['buyer_key'] =  Uuid::uuid4();

        $buyer = $this->buyer->create(array_except($validated,'deliverySchedules'));

        if($request->has('deliverySchedules') && !empty($request->get('deliverySchedules'))){
            foreach ($request->get('deliverySchedules') as $schedule) {
                $buyer->deliverySchedules()->create(array_except($schedule,'id'));
            }
        }

        return response()->json([
            'data' => $buyer
        ]);
    }

    public function update(UpdateBuyerRequest $request, $id){
        $validated = $request->validated();

        $buyer = $this->buyer->findOrFail($id);

        $buyer->update(array_except($validated,'deliverySchedules'));
        if($request->has('deliverySchedules') && !empty($request->get('deliverySchedules'))){
            $buyer->deliverySchedules()->delete();
            foreach ($request->get('deliverySchedules') as $schedule) {
                $buyer->deliverySchedules()->create(array_except($schedule,'id'));
            }
        }
        else{
            $buyer->deliverySchedules()->delete();
        }

        return response()->json([
            'data' => $buyer
        ]);
    }

    public function show($id){
        $detailBuyer = $this->buyer->with(['deliverySchedules','channels'])->findOrFail($id);
        return response()->json([
            'data' => $detailBuyer
        ]);
    }

    public function delete($id){
        $detailBuyer = $this->buyer->with(['deliverySchedules','channels'])->findOrFail($id);
        $detailBuyer->deliverySchedules()->delete();
        $detailBuyer->channels()->delete();
        $detailBuyer->delete();

        return response()->json([
            'status' => 'success'
        ]);
    }

    public function dailyStats(Request $request){
        $startDate = $request->get('start_date');
        $endDate = $request->get('end_date');
        $buyers = Buyer::get();
        $buyerStats = [];


        foreach($buyers as $buyer){
            $totalLead = 0;
            $totalAccepted = 0;
            $totalRejected = 0;
            $totalError = 0;
            $totalRedirect = 0;
            $totalRedirectSuccess = 0;
            $totalRedirectError = 0;
            $totalRedirectDuration = 0;

            $verticalStats = [];
            $platStats = [];
            $pandaStats = [];

            //Panda Channel

            $pandas = $buyer->pandaChannels()->get();

            foreach($pandas as $panda){
                $channelTotalLead = 0;
                $channelTotalAccepted = 0;
                $channelTotalRejected = 0;
                $channelTotalError = 0;
                $channelTotalRedirect = 0;
                $channelTotalRedirectSuccess = 0;
                $channelTotalRedirectError = 0;
                $channelTotalRedirectDuration = 0;

                $buyerPandaDailyReport = BuyerChannelDailyReport::whereBetween('date', [
                    Carbon::parse($startDate)->format('Y-m-d'),
                    Carbon::parse($endDate)->format('Y-m-d')
                ])->where([
                    'routeable_type' => 'App\Models\BuyerPandaChannel',
                    'routeable_id' => $panda->id,
                    'buyer_id' => $buyer->id
                ])->first();

                if(!empty($buyerPandaDailyReport)){
                    $channelTotalLead = $buyerPandaDailyReport->total_lead;
                    $channelTotalAccepted = $buyerPandaDailyReport->total_accepted;
                    $channelTotalRejected = $buyerPandaDailyReport->total_rejected;
                    $channelTotalError = $buyerPandaDailyReport->total_error;
                    $channelTotalRedirect = $buyerPandaDailyReport->total_redirect;
                    $channelTotalRedirectSuccess = $buyerPandaDailyReport->total_redirect_success;
                    $channelTotalRedirectError = $buyerPandaDailyReport->total_redirect_error;
                    $channelTotalRedirectDuration = $buyerPandaDailyReport->total_redirect_duration;

                    $totalLead += $buyerPandaDailyReport->total_lead;
                    $totalAccepted += $buyerPandaDailyReport->total_accepted;
                    $totalRejected += $buyerPandaDailyReport->total_rejected;
                    $totalError += $buyerPandaDailyReport->total_error;
                    $totalRedirect  += $buyerPandaDailyReport->total_redirect;
                    $totalRedirectSuccess += $buyerPandaDailyReport->total_redirect_success;
                    $totalRedirectError += $buyerPandaDailyReport->total_redirect_error;
                    $totalRedirectDuration += $buyerPandaDailyReport->total_redirect_duration;

                    array_push($pandaStats, [
                        'name' => $panda->channels,
                        'total_lead' => $channelTotalAccepted + $channelTotalRejected,
                        'total_accepted' => $channelTotalAccepted,
                        'total_rejected' => $channelTotalRejected,
                        'total_error' => $channelTotalError,
                        'total_redirect' => $channelTotalRedirect,
                        'total_redirect_error' => $channelTotalRedirectError,
                        'total_redirect_success' => $channelTotalRedirectSuccess,
                        'total_redirect_duration' => $channelTotalRedirectDuration
                    ]);
                }
            }

            //Vertical Channel

            $verticals = $buyer->channels()->get();

            foreach($verticals as $vertical){
                $channelTotalLead = 0;
                $channelTotalAccepted = 0;
                $channelTotalRejected = 0;
                $channelTotalError = 0;
                $channelTotalRedirect = 0;
                $channelTotalRedirectSuccess = 0;
                $channelTotalRedirectError = 0;
                $channelTotalRedirectDuration = 0;

                $buyerVerticalDailyReport = BuyerChannelDailyReport::whereBetween('date', [
                    Carbon::parse($startDate)->format('Y-m-d'),
                    Carbon::parse($endDate)->format('Y-m-d')
                ])->where([
                    'routeable_type' => 'App\Models\BuyerChannel',
                    'routeable_id' => $vertical->id,
                    'buyer_id' => $buyer->id
                ])->first();

                if(!empty($buyerVerticalDailyReport)){
                    $channelTotalLead = $buyerVerticalDailyReport->total_lead;
                    $channelTotalAccepted = $buyerVerticalDailyReport->total_accepted;
                    $channelTotalRejected = $buyerVerticalDailyReport->total_rejected;
                    $channelTotalError = $buyerVerticalDailyReport->total_error;
                    $channelTotalRedirect = $buyerVerticalDailyReport->total_redirect;
                    $channelTotalRedirectSuccess = $buyerVerticalDailyReport->total_redirect_success;
                    $channelTotalRedirectError = $buyerVerticalDailyReport->total_redirect_error;
                    $channelTotalRedirectDuration = $buyerVerticalDailyReport->total_redirect_duration;

                    $totalLead += $buyerVerticalDailyReport->total_lead;
                    $totalAccepted += $buyerVerticalDailyReport->total_accepted;
                    $totalRejected += $buyerVerticalDailyReport->total_rejected;
                    $totalError += $buyerVerticalDailyReport->total_error;
                    $totalRedirect  += $buyerVerticalDailyReport->total_redirect;
                    $totalRedirectSuccess += $buyerVerticalDailyReport->total_redirect_success;
                    $totalRedirectError += $buyerVerticalDailyReport->total_redirect_error;
                    $totalRedirectDuration += $buyerVerticalDailyReport->total_redirect_duration;

                    array_push($verticalStats, [
                        'name' => $vertical->name,
                        'total_lead' => $channelTotalAccepted + $channelTotalRejected,
                        'total_accepted' => $channelTotalAccepted,
                        'total_rejected' => $channelTotalRejected,
                        'total_error' => $channelTotalError,
                        'total_redirect' => $channelTotalRedirect,
                        'total_redirect_error' => $channelTotalRedirectError,
                        'total_redirect_success' => $channelTotalRedirectSuccess,
                        'total_redirect_duration' => $channelTotalRedirectDuration
                    ]);
                }
            }

            //Plat Channel

            $plats = $buyer->platChannels()->get();

            foreach($plats as $plat){
                $channelTotalLead = 0;
                $channelTotalAccepted = 0;
                $channelTotalRejected = 0;
                $channelTotalError = 0;
                $channelTotalRedirect = 0;
                $channelTotalRedirectSuccess = 0;
                $channelTotalRedirectError = 0;
                $channelTotalRedirectDuration = 0;

                $buyerPlatDailyReport = BuyerChannelDailyReport::whereBetween('date', [
                    Carbon::parse($startDate)->format('Y-m-d'),
                    Carbon::parse($endDate)->format('Y-m-d')
                ])->where([
                    'routeable_type' => 'App\Models\BuyerPlatChannel',
                    'routeable_id' => $plat->id,
                    'buyer_id' => $buyer->id
                ])->first();


                if(!empty($buyerPlatDailyReport)){
                    $channelTotalLead = $buyerPlatDailyReport->total_lead;
                    $channelTotalAccepted = $buyerPlatDailyReport->total_accepted;
                    $channelTotalRejected = $buyerPlatDailyReport->total_rejected;
                    $channelTotalError = $buyerPlatDailyReport->total_error;
                    $channelTotalRedirect = $buyerPlatDailyReport->total_redirect;
                    $channelTotalRedirectSuccess = $buyerPlatDailyReport->total_redirect_success;
                    $channelTotalRedirectError = $buyerPlatDailyReport->total_redirect_error;
                    $channelTotalRedirectDuration = $buyerPlatDailyReport->total_redirect_duration;

                    $totalLead += $buyerPlatDailyReport->total_lead;
                    $totalAccepted += $buyerPlatDailyReport->total_accepted;
                    $totalRejected += $buyerPlatDailyReport->total_rejected;
                    $totalError += $buyerPlatDailyReport->total_error;
                    $totalRedirect  += $buyerPlatDailyReport->total_redirect;
                    $totalRedirectSuccess += $buyerPlatDailyReport->total_redirect_success;
                    $totalRedirectError += $buyerPlatDailyReport->total_redirect_error;
                    $totalRedirectDuration += $buyerPlatDailyReport->total_redirect_duration;

                    array_push($platStats, [
                        'name' => $plat->channel_name,
                        'total_lead' => $channelTotalAccepted + $channelTotalRejected,
                        'total_accepted' => $channelTotalAccepted,
                        'total_rejected' => $channelTotalRejected,
                        'total_error' => $channelTotalError,
                        'total_redirect' => $channelTotalRedirect,
                        'total_redirect_error' => $channelTotalRedirectError,
                        'total_redirect_success' => $channelTotalRedirectSuccess,
                        'total_redirect_duration' => $channelTotalRedirectDuration
                    ]);
                }
            }



            if(count($pandaStats) > 0 || count($platStats) > 0 || count($verticalStats) > 0){
                array_push($buyerStats, [
                    'name' => $buyer->name,
                    'total_lead' => $totalAccepted + $totalRejected,
                    'total_accepted' => $totalAccepted,
                    'total_rejected' => $totalRejected,
                    'total_error' => $totalError,
                    'total_redirect' => $totalRedirect,
                    'total_redirect_error' => $totalRedirectError,
                    'total_redirect_success' => $totalRedirectSuccess,
                    'total_redirect_duration' => $totalRedirectDuration,
                    'panda_stats' => $pandaStats,
                    'vertical_stats' => $verticalStats,
                    'plat_stats' => $platStats
                ]);
            }
        }

        return response()->json([
            'data' => $buyerStats
        ]);
    }


    private function generateBuyerStats($startDate, $endDate){
        $buyers = Buyer::get();
        $buyerStats = [];

        foreach($buyers as $buyer){
            $totalLead = 0;
            $totalAccepted = 0;
            $totalRejected = 0;
            $totalError = 0;
            $totalRedirect = 0;
            $totalRedirectSuccess = 0;
            $totalRedirectError = 0;
            $totalRedirectDuration = 0;

            $verticalStats = [];
            $platStats = [];
            $pandaStats = [];

            //Panda Channel

            $pandas = $buyer->pandaChannels()->get();

            foreach($pandas as $panda){
                $channelTotalLead = 0;
                $channelTotalAccepted = 0;
                $channelTotalRejected = 0;
                $channelTotalError = 0;
                $channelTotalRedirect = 0;
                $channelTotalRedirectSuccess = 0;
                $channelTotalRedirectError = 0;
                $channelTotalRedirectDuration = 0;

                $buyerPandaDailyReport = BuyerChannelDailyReport::whereBetween('date', [
                    $startDate,
                    $endDate
                ])->where([
                    'routeable_type' => 'App\Models\BuyerPandaChannel',
                    'routeable_id' => $panda->id,
                    'buyer_id' => $buyer->id
                ])->first();

                if(!empty($buyerPandaDailyReport)){
                    $channelTotalLead = $buyerPandaDailyReport->total_lead;
                    $channelTotalAccepted = $buyerPandaDailyReport->total_accepted;
                    $channelTotalRejected = $buyerPandaDailyReport->total_rejected;
                    $channelTotalError = $buyerPandaDailyReport->total_error;
                    $channelTotalRedirect = $buyerPandaDailyReport->total_redirect;
                    $channelTotalRedirectSuccess = $buyerPandaDailyReport->total_redirect_success;
                    $channelTotalRedirectError = $buyerPandaDailyReport->total_redirect_error;
                    $channelTotalRedirectDuration = $buyerPandaDailyReport->total_redirect_duration;

                    $totalLead += $buyerPandaDailyReport->total_lead;
                    $totalAccepted += $buyerPandaDailyReport->total_accepted;
                    $totalRejected += $buyerPandaDailyReport->total_rejected;
                    $totalError += $buyerPandaDailyReport->total_error;
                    $totalRedirect  += $buyerPandaDailyReport->total_redirect;
                    $totalRedirectSuccess += $buyerPandaDailyReport->total_redirect_success;
                    $totalRedirectError += $buyerPandaDailyReport->total_redirect_error;
                    $totalRedirectDuration += $buyerPandaDailyReport->total_redirect_duration;

                    array_push($pandaStats, [
                        'name' => $panda->channels,
                        'total_lead' => $channelTotalAccepted + $channelTotalRejected,
                        'total_accepted' => $channelTotalAccepted,
                        'total_rejected' => $channelTotalRejected,
                        'total_error' => $channelTotalError,
                        'total_redirect' => $channelTotalRedirect,
                        'total_redirect_error' => $channelTotalRedirectError,
                        'total_redirect_success' => $channelTotalRedirectSuccess,
                        'total_redirect_duration' => $channelTotalRedirectDuration
                    ]);
                }
            }

            //Vertical Channel

            $verticals = $buyer->channels()->get();

            foreach($verticals as $vertical){
                $channelTotalLead = 0;
                $channelTotalAccepted = 0;
                $channelTotalRejected = 0;
                $channelTotalError = 0;
                $channelTotalRedirect = 0;
                $channelTotalRedirectSuccess = 0;
                $channelTotalRedirectError = 0;
                $channelTotalRedirectDuration = 0;

                $buyerVerticalDailyReport = BuyerChannelDailyReport::whereBetween('date', [
                    $startDate,
                    $endDate
                ])->where([
                    'routeable_type' => 'App\Models\BuyerChannel',
                    'routeable_id' => $vertical->id,
                    'buyer_id' => $buyer->id
                ])->first();

                if(!empty($buyerVerticalDailyReport)){
                    $channelTotalLead = $buyerVerticalDailyReport->total_lead;
                    $channelTotalAccepted = $buyerVerticalDailyReport->total_accepted;
                    $channelTotalRejected = $buyerVerticalDailyReport->total_rejected;
                    $channelTotalError = $buyerVerticalDailyReport->total_error;
                    $channelTotalRedirect = $buyerVerticalDailyReport->total_redirect;
                    $channelTotalRedirectSuccess = $buyerVerticalDailyReport->total_redirect_success;
                    $channelTotalRedirectError = $buyerVerticalDailyReport->total_redirect_error;
                    $channelTotalRedirectDuration = $buyerVerticalDailyReport->total_redirect_duration;

                    $totalLead += $buyerVerticalDailyReport->total_lead;
                    $totalAccepted += $buyerVerticalDailyReport->total_accepted;
                    $totalRejected += $buyerVerticalDailyReport->total_rejected;
                    $totalError += $buyerVerticalDailyReport->total_error;
                    $totalRedirect  += $buyerVerticalDailyReport->total_redirect;
                    $totalRedirectSuccess += $buyerVerticalDailyReport->total_redirect_success;
                    $totalRedirectError += $buyerVerticalDailyReport->total_redirect_error;
                    $totalRedirectDuration += $buyerVerticalDailyReport->total_redirect_duration;

                    array_push($verticalStats, [
                        'name' => $vertical->name,
                        'total_lead' => $channelTotalAccepted + $channelTotalRejected,
                        'total_accepted' => $channelTotalAccepted,
                        'total_rejected' => $channelTotalRejected,
                        'total_error' => $channelTotalError,
                        'total_redirect' => $channelTotalRedirect,
                        'total_redirect_error' => $channelTotalRedirectError,
                        'total_redirect_success' => $channelTotalRedirectSuccess,
                        'total_redirect_duration' => $channelTotalRedirectDuration
                    ]);
                }
            }

            //Plat Channel

            $plats = $buyer->platChannels()->get();

            foreach($plats as $plat){
                $channelTotalLead = 0;
                $channelTotalAccepted = 0;
                $channelTotalRejected = 0;
                $channelTotalError = 0;
                $channelTotalRedirect = 0;
                $channelTotalRedirectSuccess = 0;
                $channelTotalRedirectError = 0;
                $channelTotalRedirectDuration = 0;

                $buyerPlatDailyReport = BuyerChannelDailyReport::whereBetween('date', [
                    $startDate,
                    $endDate
                ])->where([
                    'routeable_type' => 'App\Models\BuyerPlatChannel',
                    'routeable_id' => $plat->id,
                    'buyer_id' => $buyer->id
                ])->first();


                if(!empty($buyerPlatDailyReport)){
                    $channelTotalLead = $buyerPlatDailyReport->total_lead;
                    $channelTotalAccepted = $buyerPlatDailyReport->total_accepted;
                    $channelTotalRejected = $buyerPlatDailyReport->total_rejected;
                    $channelTotalError = $buyerPlatDailyReport->total_error;
                    $channelTotalRedirect = $buyerPlatDailyReport->total_redirect;
                    $channelTotalRedirectSuccess = $buyerPlatDailyReport->total_redirect_success;
                    $channelTotalRedirectError = $buyerPlatDailyReport->total_redirect_error;
                    $channelTotalRedirectDuration = $buyerPlatDailyReport->total_redirect_duration;

                    $totalLead += $buyerPlatDailyReport->total_lead;
                    $totalAccepted += $buyerPlatDailyReport->total_accepted;
                    $totalRejected += $buyerPlatDailyReport->total_rejected;
                    $totalError += $buyerPlatDailyReport->total_error;
                    $totalRedirect  += $buyerPlatDailyReport->total_redirect;
                    $totalRedirectSuccess += $buyerPlatDailyReport->total_redirect_success;
                    $totalRedirectError += $buyerPlatDailyReport->total_redirect_error;
                    $totalRedirectDuration += $buyerPlatDailyReport->total_redirect_duration;

                    array_push($platStats, [
                        'name' => $plat->channel_name,
                        'total_lead' => $channelTotalAccepted + $channelTotalRejected,
                        'total_accepted' => $channelTotalAccepted,
                        'total_rejected' => $channelTotalRejected,
                        'total_error' => $channelTotalError,
                        'total_redirect' => $channelTotalRedirect,
                        'total_redirect_error' => $channelTotalRedirectError,
                        'total_redirect_success' => $channelTotalRedirectSuccess,
                        'total_redirect_duration' => $channelTotalRedirectDuration
                    ]);
                }
            }



            if(count($pandaStats) > 0 || count($platStats) > 0 || count($verticalStats) > 0){
                array_push($buyerStats, [
                    'name' => $buyer->name,
                    'total_lead' => $totalAccepted + $totalRejected,
                    'total_accepted' => $totalAccepted,
                    'total_rejected' => $totalRejected,
                    'total_error' => $totalError,
                    'total_redirect' => $totalRedirect,
                    'total_redirect_error' => $totalRedirectError,
                    'total_redirect_success' => $totalRedirectSuccess,
                    'total_redirect_duration' => $totalRedirectDuration,
                    'panda_stats' => $pandaStats,
                    'vertical_stats' => $verticalStats,
                    'plat_stats' => $platStats
                ]);
            }

        }

        $finalStats = [];
        foreach ($buyerStats as $stat) {
            $redirected =  $stat['total_accepted'] > 0 ? ($stat['total_redirect_success'] / $stat['total_accepted']) * 100 : 0;
            $notRedirect =  $stat['total_accepted'] > 0 ? (($stat['total_accepted'] - $stat['total_redirect_success']) / $stat['total_accepted']) * 100 : 0;

            $pandaStats = [];
            foreach ($stat['panda_stats'] as $pandaStat) {
                $pandaRedirected = $pandaStats['total_accepted'] > 0 ? ($pandaStat['total_redirect_success'] / $pandaStat['total_accepted']) * 100 : 0;
                $pandaNotRedirect = $pandaStats['total_accepted'] > 0 ? (($pandaStat['total_accepted'] - $pandaStat['total_redirect_success']) / $pandaStat['total_accepted']) * 100 : 0;

                array_push($pandaStats, [
                    'name' => $pandaStat['name'],
                    'total_lead' => $pandaStat['total_lead'],
                    'total_accepted' => $pandaStat['total_accepted'],
                    'total_rejected' => $pandaStat['total_rejected'],
                    'total_error' => $pandaStat['total_error'],
                    'redirected' => number_format($pandaRedirected, 2),
                    'not_redirect' => number_format($pandaNotRedirect, 2),
                ]);
            }


            $verticalStats = [];
            foreach ($stat['vertical_stats'] as $verticalStat) {
                $verticalRedirected = $verticalStat['total_accepted'] > 0 ? ($verticalStat['total_redirect_success'] / $verticalStat['total_accepted']) * 100 : 0;
                $verticalNotRedirect = $verticalStat['total_accepted'] > 0 ? (($verticalStat['total_accepted'] - $verticalStat['total_redirect_success']) / $verticalStat['total_accepted']) * 100 : 0;

                array_push($verticalStats, [
                    'name' => $verticalStat['name'],
                    'total_lead' => $verticalStat['total_lead'],
                    'total_accepted' => $verticalStat['total_accepted'],
                    'total_rejected' => $verticalStat['total_rejected'],
                    'total_error' => $verticalStat['total_error'],
                    'redirected' => number_format($verticalRedirected, 2),
                    'not_redirect' => number_format($verticalNotRedirect, 2),
                ]);
            }

            $platStats = [];
            foreach ($stat['plat_stats'] as $platStat) {
                $platRedirected =  $platStat['total_accepted'] > 0 ? ($platStat['total_redirect_success'] / $platStat['total_accepted']) * 100 : 0;
                $platNotRedirect = $platStat['total_accepted'] > 0 ? (($platStat['total_accepted'] - $platStat['total_redirect_success']) / $platStat['total_accepted']) * 100 : 0;

                array_push($platStats, [
                    'name' => $verticalStat['name'],
                    'total_lead' => $platStat['total_lead'],
                    'total_accepted' => $platStat['total_accepted'],
                    'total_rejected' => $platStat['total_rejected'],
                    'total_error' => $platStat['total_error'],
                    'redirected' => number_format($platRedirected, 2),
                    'not_redirect' => number_format($platNotRedirect, 2),
                ]);
            }

            array_push($finalStats, [
                'name' => $stat['name'],
                'total_lead' => $stat['total_lead'],
                'total_accepted' => $stat['total_accepted'],
                'total_rejected' => $stat['total_rejected'],
                'total_error' => $stat['total_error'],
                'redirected' => number_format($redirected, 2),
                'not_redirect' => number_format($notRedirect, 2),
                'panda_stats' => $pandaStats,
                'vertical_stats' => $verticalStats,
                'plat_stats' => $platStats
            ]);
        }

        return $finalStats;
    }




    public function countByBuyerStatsExport(Request $request){
        $startDate = Carbon::parse($request->get('start_date'));
        $endDate = Carbon::parse($request->get('end_date'));
        $buyerStats = $this->generateBuyerStats($startDate->format('Y-m-d'), $endDate->format('Y-m-d'));
            
        $pdf = \PDF::loadView('pdf.count-by-buyer',[
            'startDate' => $startDate->format('m/d/Y'),
            'endDate' => $endDate->format('m/d/Y'),
            'buyerStats' => $buyerStats
        ]);
        return $pdf->download('Count by Buyer Report.pdf');
    }

    public function countByBuyerStatsSendEmail(CreateCountByBuyerReportRequest $request){
        $validated = $request->validated();
        $startDate = Carbon::parse($request->get('start_date'));
        $endDate = Carbon::parse($request->get('end_date'));

        $buyerStats = $this->generateBuyerStats($startDate->format('Y-m-d'), $endDate->format('Y-m-d'));

        $mail = Mail::to($validated['email']);
        $mail->send(new CountByBuyerReport($buyerStats, $startDate->format('m/d/Y'), $endDate->format('m/d/Y')));

        return response()->json([
            'status' => 'success'
        ]);
    }

}
