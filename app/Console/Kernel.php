<?php

namespace App\Console;

use App\Setting;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        //Schedular for fake lead generaton
        $number_of_fake_lead_per_minute = Setting::where('name', 'number_of_fake_lead_per_minute')->first();
        Log::info($number_of_fake_lead_per_minute);
        if(!empty($number_of_fake_lead_per_minute) && $number_of_fake_lead_per_minute->value > 0){
            $record_per_minute = $number_of_fake_lead_per_minute->value;
            $ms = 60000/$record_per_minute;

            $schedule->call(function () use ($ms, $record_per_minute) {

                $dt = Carbon::now();
                $dt_end = $dt->addMinute();

                do{

                    Artisan::call('db:seed',['--class' => 'ApplicationSeeder']);

                    usleep($ms*1000);

                } while($record_per_minute-- > 0 && Carbon::now() < $dt_end );
            })->everyMinute();
        }

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
