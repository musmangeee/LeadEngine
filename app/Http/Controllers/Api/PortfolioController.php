<?php

namespace App\Http\Controllers\Api;

use App\Models\Buyer;
use Ramsey\Uuid\Uuid;
use App\Models\Portfolio;
use Illuminate\Support\Arr;
use App\Models\BuyerChannel;
use App\Models\WidgetReport;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\BuyerPlatChannel;
use App\Models\PortfolioRouting;
use App\Models\BuyerPandaChannel;
use App\Http\Controllers\Controller;
use App\Models\PortfolioBuyerDailyReport;
use App\Http\Requests\Api\CreatePortfolioRequest;
use App\Http\Requests\Api\UpdatePortfolioRequest;

class PortfolioController extends Controller
{
    private $portfolioRouting;
    private $portfolio;
    private $buyers;

    public function __construct(Portfolio $portfolio, PortfolioRouting $portfolioRouting, Buyer $buyers)
    {
        $this->portfolioRouting = $portfolioRouting;
        $this->portfolio = $portfolio;
        $this->buyers = $buyers;
    }

    public function index(Request $request){
        $portfolios = $this->portfolio->newQuery();

        $perPage = $request->has('perPage') ? (int) $request->perPage : 10;

        $results = $portfolios->paginate($perPage);
        return response()->json($results, 200);
    }

    public function create(CreatePortfolioRequest $request){
        $validated = $request->validated();
        $validated['portfolio_key'] = Uuid::uuid4();
        $portfolio = $this->portfolio->create($validated);

        if(!empty($validated['routings'])){
            foreach($validated['routings'] as $routing){
                $portfolio->routings->create($routing);
            }
        }

        return response()->json([
            'data' => $portfolio
        ]);
    }

    public function show($id){
        $portfolio = $this->portfolio->findOrFail($id);
        $results = [];
        $routings = $this->portfolioRouting->where('portfolio_id',$portfolio->id)->orderBy('priority')->get();
        foreach ($routings as $routing) {
            $routeData = $routing->routeable->toArray();
            if($routing->routeable_type === BuyerChannel::class){
                $routeData['channel_type'] = 'vertical';
            }
            else if($routing->routeable_type === BuyerPlatChannel::class){
                $routeData['channel_type'] = 'plat';

            }
            else if($routing->routeable_type === BuyerPandaChannel::class){
                $routeData['channel_type'] = 'panda';
            }
            $routeData['buyer_name'] =  $routing->routeable->buyer->name;
            $routeData['routeable_id'] = $routing->routeable->id;
            $routeData['routeable_type'] = get_class($routing->routeable);
            $routeData['traffic_percentage'] = $routing->traffic_percentage;
            $routeData['minimized'] = 0;
            array_push($results, $routeData);
        }
        $portfolio['routings'] = $results;

        return response()->json([
            'data' => $portfolio
        ]);
    }



    public function update($id, UpdatePortfolioRequest $request){
        $validated = $request->validated();
        $portfolio = $this->portfolio->findOrFail($id);
        $portfolio->update(Arr::except($validated, 'routings'));

        if(!empty($validated['routings'])){
            //$portfolio->routings()->delete();
            /*foreach($validated['routings'] as $key => $routing){
                $portfolio_routing = $portfolio->routings()->where('routeable_id', $routing['routeable_id'])->where('routeable_type', $routing['routeable_type'])->first();
                if($portfolio_routing){
                    $portfolio_routing->traffic_percentage = $routing['traffic_percentage'];
                    $portfolio_routing->priority = $key;
                    $portfolio_routing->save();
                }else{
                    $routingData = Arr::only($routing,['routeable_id','routeable_type','traffic_percentage']);
                    $routingData['priority'] = $key;
                    $portfolio->routings()->create($routingData);
                }
            }*/
            $portfolio->routings()->delete();
                foreach($validated['routings'] as $key => $routing){
                    $routingData = Arr::only($routing,['routeable_id','routeable_type','traffic_percentage']);
                    $routingData['priority'] = $key;
                    $portfolio->routings()->create($routingData);
                }
        }

        return response()->json([
            'data' => $portfolio
        ]);
    }

    public function delete($id){
        $portfolio = $this->portfolio->findOrFail($id);
        $portfolio->routings()->delete();
        $portfolio->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }

    public function routingList(){
        $results = collect();
        $buyers = $this->buyers->with(['channels' => function($q){
            $q->orderBy('price','asc');
        },'pandaChannels','platChannels' => function($q){
            $q->orderBy('price','asc');
        }])->get();
        foreach ($buyers as $buyer) {
           $data = [
               'buyer_name' => $buyer->name,
               'vertical' => [],
               'plat' => [],
               'panda' => []
           ];

           foreach ($buyer->channels as $channel) {
                $verticalData = $channel;
                $verticalData['channel_type'] = 'vertical';
                $verticalData['buyer_name'] = $buyer->name;
                $verticalData['routeable_id'] = $channel->id;
                $verticalData['routeable_type'] = get_class($channel);
                $verticalData['traffic_percentage'] = 10;
                $verticalData['minimized'] = 0;
                array_push($data['vertical'], $verticalData);
           }

           foreach ($buyer->platChannels as $channel) {
                $platData = $channel;
                $platData['channel_type'] = 'plat';
                $platData['buyer_name'] = $buyer->name;
                $platData['routeable_id'] = $channel->id;
                $platData['routeable_type'] = get_class($channel);
                $platData['traffic_percentage'] = 10;
                $platData['minimized'] = 0;

                array_push($data['plat'], $platData);
            }

            foreach ($buyer->pandaChannels as $channel) {
                $pandaData = $channel;
                $pandaData['channel_type'] = 'panda';
                $pandaData['buyer_name'] = $buyer->name;
                $pandaData['routeable_id'] = $channel->id;
                $pandaData['routeable_type'] = get_class($channel);
                $pandaData['traffic_percentage'] = 10;
                $pandaData['minimized'] = 0;

                array_push($data['panda'], $pandaData);
            }

           $results->push($data);
        }

        return response()->json([
            'data' => $results->toArray()
        ]);
    }

    public function dailyStats(Request $request){
        $selectedDate = $request->get('selected_date');
        $portfolios = Portfolio::get();
        $buyers = Buyer::get();
        $portfolioStats = [];
       
        foreach($portfolios as $portfolio){
            $totalLead = 0;
            $totalAccepted = 0;
            $totalRejected = 0;
            $totalError = 0;
            $totalRedirect = 0;
            $totalRedirectSuccess = 0;
            $totalRedirectError = 0;
            $totalRedirectDuration = 0;
            $buyerStats = [];

            foreach($buyers as $buyer){
                $buyerTotalAccepted = 0;
                $buyerTotalRejected = 0;
                $buyerTotalError = 0;
                $buyerTotalRedirect = 0;
                $buyerTotalRedirectSuccess = 0;
                $buyerTotalRedirectError = 0;
                $buyerTotalRedirectDuration = 0;

                $portfolioBuyerDailyReport = PortfolioBuyerDailyReport::where([
                    'date' => Carbon::parse($selectedDate)->format('Y-m-d'),
                    'portfolio_id' => $portfolio->id,
                    'buyer_id' => $buyer->id
                ])->first();

                if(!empty($portfolioBuyerDailyReport)){
                    $buyerTotalAccepted = $portfolioBuyerDailyReport->total_accepted;
                    $buyerTotalRejected = $portfolioBuyerDailyReport->total_rejected;
                    $buyerTotalError = $portfolioBuyerDailyReport->total_error;
                    $buyerTotalRedirect = $portfolioBuyerDailyReport->total_redirect;
                    $buyerTotalRedirectSuccess = $portfolioBuyerDailyReport->total_redirect_success;
                    $buyerTotalRedirectError = $portfolioBuyerDailyReport->total_redirect_error;
                    $buyerTotalRedirectDuration = $portfolioBuyerDailyReport->total_redirect_duration;

                    $totalLead += $portfolioBuyerDailyReport->total_lead;
                    $totalAccepted += $portfolioBuyerDailyReport->total_accepted;
                    $totalRejected += $portfolioBuyerDailyReport->total_rejected;
                    $totalError += $portfolioBuyerDailyReport->total_error;
                    $totalRedirect  += $portfolioBuyerDailyReport->total_redirect;
                    $totalRedirectSuccess += $portfolioBuyerDailyReport->total_redirect_success;
                    $totalRedirectError += $portfolioBuyerDailyReport->total_redirect_error;
                    $totalRedirectDuration += $portfolioBuyerDailyReport->total_redirect_duration;

                    array_push($buyerStats, [
                        'name' => $buyer->name,
                        'total_lead' => $buyerTotalAccepted + $buyerTotalRejected,
                        'total_accepted' => $buyerTotalAccepted,
                        'total_rejected' => $buyerTotalRejected,
                        'total_error' => $buyerTotalError,
                        'total_redirect' => $buyerTotalRedirect,
                        'total_redirect_error' => $buyerTotalRedirectError,
                        'total_redirect_success' => $buyerTotalRedirectSuccess,
                        'total_redirect_duration' => $buyerTotalRedirectDuration
                    ]);
                }
            }

            if(count($buyerStats) > 0){
                array_push($portfolioStats, [
                    'name' => $portfolio->name,
                    'total_lead' => $totalAccepted + $totalRejected,
                    'total_accepted' => $totalAccepted,
                    'total_rejected' => $totalRejected,
                    'total_error' => $totalError,
                    'total_redirect' => $totalRedirect,
                    'total_redirect_error' => $totalRedirectError,
                    'total_redirect_success' => $totalRedirectSuccess,
                    'total_redirect_duration' => $totalRedirectDuration,
                    'buyer_stats' => $buyerStats
                ]);
            }
        }
        $result = WidgetReport::whereBetween('date', [$request->startDate, $request->endDate])->orderBy('date','desc')->get();
        return response()->json([
            'data' => $portfolioStats,
            'data' => $result
        ]);
    }
}
