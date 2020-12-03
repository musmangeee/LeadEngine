<?php

namespace App\Models;

use App\Models\ProviderDailyReport;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Dyrynda\Database\Support\GeneratesUuid;
use Dyrynda\Database\Casts\EfficientUuid;

class Application extends Model
{
    use GeneratesUuid;

    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'address_months' => 'integer',
        'address_years' => 'integer',
        'affid' => 'integer',
        'dl_number' => 'integer',
        'emp_months' => 'integer',
        'emp_years' => 'integer',
        'net_monthly_income' => 'integer',
        'next_pay_date' => 'date',
        'last_pay_date' => 'date',
        'second_pay_date' => 'date',
        'hire_date' => 'date',
        'requested_amount' => 'integer',
        'routing_number' => 'integer',
        'ssn' => 'integer',
        'uuid' => EfficientUuid::class,
    ];

    public static function boot()
    {
        parent::boot();

        Application::creating(function($application){
            $widget_report = WidgetReport::where('date',Carbon::today()->format('Y-m-d'))->first();
            if($widget_report){
                $widget_report->increment('total_lead', 1);
                $widget_report->save();
            }else{
                WidgetReport::create([
                    'date' => Carbon::today()->format('Y-m-d'),
                    'total_lead' => 1
                ]);
            }
        });

        Application::created(function($application){
            $providerDailyReport = ProviderDailyReport::where([
                'date' => today()->format('Y-m-d'),
                'provider_id' => $application->provider_id
            ])->first();
            if($providerDailyReport){
                $providerDailyReport->increment('total_lead',1);
            }
            else{
                ProviderDailyReport::create([
                    'provider_id' => $application->provider_id,
                    'date' => today()->format('Y-m-d'),
                    'total_lead' => 1
                ]);
            }
        });


    }

    public function status(){
        return $this->hasMany(ApplicationStatus::class);
    }

    public function provider(){
        return $this->belongsTo(Provider::class);
    }

    public function redirect(){
        return $this->hasOne(ApplicationRedirect::class);
    }
}
