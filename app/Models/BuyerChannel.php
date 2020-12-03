<?php

namespace App\Models;

use App\Models\Buyer;
use Illuminate\Database\Eloquent\Model;

class BuyerChannel extends Model
{
    protected $guarded = [];

    public function buyer(){
        return $this->hasOne(Buyer::class,'id','buyer_id');
    }

    public function deliverySchedules(){
        return $this->hasMany(BuyerChannelDeliverySchedule::class);
    }

    public function portfolioRoutings(){
        return $this->morphMany(PortfolioRouting::class,'routeable');
    }
}
