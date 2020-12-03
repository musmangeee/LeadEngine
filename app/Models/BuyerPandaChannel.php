<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuyerPandaChannel extends Model
{
    protected $guarded = [];

    public function deliverySchedules(){
        return $this->hasMany(BuyerPandaChannelDeliverySchedules::class);
    }

    public function buyer(){
        return $this->belongsTo(Buyer::class);
    }

    public function portfolioRoutings(){
        return $this->morphMany(PortfolioRouting::class,'routeable');
    }

}
