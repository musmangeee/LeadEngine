<?php

namespace App\Models;

use App\Models\Portfolio;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class PortfolioBuyerDailyReport extends Model
{
    protected $guarded= [];
    protected $appends = ['formatted_date'];

    public function getFormattedDateAttribute()
    {
        return Carbon::createFromFormat('Y-m-d', $this->date)->format('m/d');
    }

    public function portfolio(){
        return $this->belongsTo(Portfolio::class);
    }

    public function buyer(){
        return $this->belongsTo(Buyer::class);
    }
}
