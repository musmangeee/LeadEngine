<?php

namespace App\Models;

use App\Models\ProviderDailyReport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ApplicationStatus extends Model
{
    const STATUS_SOLD = 'sold';
    const STATUS_REJECT = 'reject';
    const STATUS_ERROR = 'error';

    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        ApplicationStatus::creating(function($application_status){
            $widget_report = WidgetReport::where('date',Carbon::today()->format('Y-m-d'))->first();
            if($application_status->status == 'accepted'){
                $widget_report->increment('total_accepted', 1);
            }

            if($application_status->status == 'rejected'){
                $widget_report->increment('total_rejected', 1);
            }

            if($application_status->status == 'error'){
                $widget_report->increment('total_error', 1);
            }

            $widget_report->save();

        });

        ApplicationStatus::created(function($application_status){
            $providerDailyReport = ProviderDailyReport::where([
                'date' => today()->format('Y-m-d'),
                'provider_id' => $application_status->application->provider_id,
            ])->first();
            
            if($application_status->status == 'accepted'){
                $providerDailyReport->increment('total_accepted', 1);
            }

            if($application_status->status == 'rejected'){
                $providerDailyReport->increment('total_rejected', 1);
            }

            if($application_status->status == 'error'){
                $providerDailyReport->increment('total_error', 1);
            }
            $providerDailyReport->save();
        });

        ApplicationStatus::updated(function($application_status){
            $widget_report = WidgetReport::where('date',Carbon::today()->format('Y-m-d'))->first();
            if($application_status->status == 'accepted'){
                $widget_report->increment('total_accepted', 1);
            }

            if($application_status->status == 'rejected'){
                $widget_report->increment('total_rejected', 1);
            }

            if($application_status->status == 'error'){
                $widget_report->increment('total_error', 1);
            }

            $widget_report->save();


            $providerDailyReport = ProviderDailyReport::where([
                'date' => today()->format('Y-m-d'),
                'provider_id' => $application_status->application->provider_id,
            ])->first();
            
            if($application_status->status == 'accepted'){
                $providerDailyReport->increment('total_accepted', 1);
            }

            if($application_status->status == 'rejected'){
                $providerDailyReport->increment('total_rejected', 1);
            }

            if($application_status->status == 'error'){
                $providerDailyReport->increment('total_error', 1);
            }
            $providerDailyReport->save();
        });
    }

    public function application(){
        return $this->belongsTo(Application::class);
    }
}
