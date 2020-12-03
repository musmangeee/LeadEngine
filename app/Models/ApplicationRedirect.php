<?php

namespace App\Models;

use App\Models\ProviderDailyReport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ApplicationRedirect extends Model
{
    protected $guarded = [];

    public static function boot()
    {
        parent::boot();

        ApplicationRedirect::updated(function($application_redirect){
            $widget_report = WidgetReport::where('date',Carbon::today()->format('Y-m-d'))->first();

            if($application_redirect->redirected_at != null && $application_redirect->failed_reason == null){
                $widget_report->increment('total_redirect_success', 1);
            }else{
                $widget_report->increment('total_redirect_error', 1);
            }

            if($application_redirect->redirected_at != null){
                $diff = strtotime($application_redirect->redirected_at) - strtotime($application_redirect->created_at);
                $widget_report->increment('total_redirect_duration', $diff);
            }

            $widget_report->save();

            $providerDailyReport = ProviderDailyReport::where([
                'date' => today()->format('Y-m-d'),
                'provider_id' => $application_redirect->application->provider_id,
            ])->first();

            if($application_redirect->redirected_at != null && $application_redirect->failed_reason == null){
                $providerDailyReport->increment('total_redirect_success', 1);
            }else{
                $providerDailyReport->increment('total_redirect_error', 1);
            }

            if($application_redirect->redirected_at != null){
                $diff = strtotime($application_redirect->redirected_at) - strtotime($application_redirect->created_at);
                $providerDailyReport->increment('total_redirect_duration', $diff);
            }

        });
    }

    public function application(){
        return $this->belongsTo(Application::class);
    }
}
