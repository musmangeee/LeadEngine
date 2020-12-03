<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuyerPlatChannel extends Model
{
    protected $guarded = [];

    public function deliverySchedules(){
        return $this->hasMany(BuyerPlatChannelDeliverySchedules::class);
    }

    public function buyer(){
        return $this->belongsTo(Buyer::class);
    }

    public function portfolioRoutings(){
        return $this->morphMany(PortfolioRouting::class,'routeable');
    }

}
