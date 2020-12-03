<?php

namespace App\Models;

use App\Models\Provider;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ProviderDailyReport extends Model
{
    protected $guarded= [];
    protected $appends = ['formatted_date'];

    public function getFormattedDateAttribute()
    {
        return Carbon::createFromFormat('Y-m-d', $this->date)->format('m/d');
    }

    public function provider(){
        return $this->belongsTo(Provider::class);
    }
}
