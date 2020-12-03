<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\Provider;
use App\Models\ProviderDailyReport;
use Carbon\CarbonPeriod;
use Illuminate\Console\Command;

class RegenerateDailyCountByProvider extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'regenerate:daily-count-by-provider';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate data for provider_daily_reports table';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $results = [];

        $providers = Provider::get();
        foreach ($providers as $provider) {
            $firstApplication = Application::where('provider_id',$provider->id)->orderBy('created_at','asc')->first();
            $lastApplication = Application::where('provider_id',$provider->id)->orderBy('created_at','desc')->first();
            if(!empty($firstApplication) && !empty($lastApplication)){
                $periods = CarbonPeriod::create($firstApplication->created_at->format('Y-m-d'), $lastApplication->created_at->format('Y-m-d'));
                foreach ($periods as $currentDate) {
                    $totalLead = Application::whereDate('created_at',$currentDate->format('Y-m-d'))
                    ->where('provider_id',$provider->id)->count();

                    $totalAccepted = Application::whereDate('created_at',$currentDate->format('Y-m-d'))
                    ->where('provider_id',$provider->id)
                    ->whereHas('status',function($q){
                        $q->where('status','accepted');
                    })->count();

                    $totalRejected = Application::whereDate('created_at',$currentDate->format('Y-m-d'))
                    ->where('provider_id',$provider->id)
                    ->whereHas('status',function($q){
                        $q->where('status','rejected');
                    })->count();

                    $totalError = Application::whereDate('created_at',$currentDate->format('Y-m-d'))
                    ->where('provider_id',$provider->id)
                    ->whereHas('status',function($q){
                        $q->where('status','error');
                    })->count();

                    $totalRedirectSuccess =  Application::whereDate('created_at',$currentDate->format('Y-m-d'))
                    ->where('provider_id',$provider->id)
                    ->whereHas('redirect', function($q){
                        $q->whereNotNull('redirected_at')->whereNull('failed_reason');
                    })->count();

                    $totalRedirectError =  Application::whereDate('created_at',$currentDate->format('Y-m-d'))
                    ->where('provider_id',$provider->id)
                    ->whereHas('redirect', function($q){
                        $q->whereNull('redirected_at')->whereNotNull('failed_reason');
                    })->count();

                    $totalRedirectDuration = Application::whereDate('created_at',$currentDate->format('Y-m-d'))
                    ->where('provider_id',$provider->id)
                    ->whereHas('redirect', function($q){
                        $q->whereNotNull('redirected_at');
                    })->get()->sum(function($redirect){
                        $diff = strtotime($redirect->created_at) - strtotime($redirect->redirected_at);
                        return $diff;
                    });

                    $exProviderDailyReport = ProviderDailyReport::where([
                        'provider_id' => $provider->id,
                        'date' => $currentDate->format('Y-m-d')
                    ])->first();
                    if(!empty($exProviderDailyReport)){
                        $exProviderDailyReport->update([
                            'total_lead' => $totalLead,
                            'total_accepted' => $totalAccepted,
                            'total_rejected' => $totalRejected,
                            'total_error' => $totalError,
                            'total_redirect_success' => $totalRedirectSuccess,
                            'total_redirect_error' => $totalRedirectError,
                            'total_redirect_duration' => $totalRedirectDuration
                        ]);
                    }
                    else{
                        ProviderDailyReport::create([
                            'provider_id' => $provider->id,
                            'date' => $currentDate->format('Y-m-d'),
                            'total_lead' => $totalLead,
                            'total_accepted' => $totalAccepted,
                            'total_rejected' => $totalRejected,
                            'total_error' => $totalError,
                            'total_redirect_success' => $totalRedirectSuccess,
                            'total_redirect_error' => $totalRedirectError,
                            'total_redirect_duration' => $totalRedirectDuration
                        ]);
                    }   

                   
                    
                }
            }
        }
    }
}
