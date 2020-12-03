<?php

namespace App\Console\Commands;

use App\Models\Application;
use App\Models\WidgetReport;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class regenerateAllWidgetData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:regenerateAllWidgetData';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Regenerate all widget data';

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

        $total_success = DB::table('application_statuses')
            ->where('status', 'accepted')
            ->orWhere('status', 'sold')
            ->select(DB::raw('count(*) as count, DATE(created_at) as date'))
            ->groupBy('date')->get()->mapWithKeys(function($application){
                return [
                    $application->date => [
                        "total_accepted" => $application->count,
                        "total_rejected" => 0,
                        "total_error" => 0,
                        "total_redirect_success" => 0,
                        "total_redirect_error" => 0,
                        "total_redirect_duration" => 0
                        ]
                    ];
        });

        $total_rejected = DB::table('application_statuses')
            ->where('status', 'rejected')
            ->orWhere('status', 'reject')
            ->select(DB::raw('count(*) as count, DATE(created_at) as date'))
            ->groupBy('date')->get()->mapWithKeys(function($application){
                return [
                    $application->date => [
                        "total_rejected" => $application->count
                        ]
                    ];
        });

        $total_error = DB::table('application_statuses')
            ->where('status', 'error')
            ->select(DB::raw('count(*) as count, DATE(created_at) as date'))
            ->groupBy('date')->get()->mapWithKeys(function($application){
                return [
                    $application->date => [
                        "total_error" => $application->count
                        ]
                    ];
        });

        $total_redirect_success = DB::table('application_redirects')
            ->whereNotNull('redirected_at')
            ->whereNull('failed_reason')
            ->select(DB::raw('count(*) as count, DATE(created_at) as date'))
            ->groupBy('date')->get()->mapWithKeys(function($application){
                return [
                    $application->date => [
                        "total_redirect_success" => $application->count
                        ]
                    ];
        });

        $total_redirect_error = DB::table('application_redirects')
            ->whereNotNull('redirected_at')
            ->whereNotNull('failed_reason')
            ->select(DB::raw('count(*) as count, DATE(created_at) as date'))
            ->groupBy('date')->get()->mapWithKeys(function($application){
                return [
                    $application->date => [
                        "total_redirect_error" => $application->count
                        ]
                    ];
        });

        $total_redirect_duration = DB::table('application_redirects')
            ->select(DB::raw('sum(TIMESTAMPDIFF(SECOND, created_at, redirected_at)) as secondsDuration, DATE(created_at) as date'))
            ->groupBy('date')->get()->mapWithKeys(function($application){
                return [
                    $application->date => [
                        "total_redirect_duration" => (int) $application->secondsDuration
                        ]
                    ];
        });


        $merged = array_replace_recursive(
            $total_success->toArray(),
            $total_rejected->toArray(),
            $total_error->toArray(),
            $total_redirect_success->toArray(),
            $total_redirect_error->toArray(),
            $total_redirect_duration->toArray()
        );

        foreach($merged as $key => $item){
            $item['date'] = $key;
            $item['total_lead'] = $item['total_accepted'] + $item['total_rejected'] + $item['total_error'];
            $itam['total_redirect'] = $item['total_redirect_success'] + $item['total_redirect_error'];

            WidgetReport::create($item);
        }




    }
}
