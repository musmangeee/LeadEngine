<?php

namespace App\Models;

use App\Models\BuyerChannel;
use App\Models\BuyerDeliverySchedule;
use Illuminate\Database\Eloquent\Model;

class Buyer extends Model
{
    protected $guarded = [];

    public function deliverySchedules(){
        return $this->hasMany(BuyerDeliverySchedule::class);
    }

    public function channels(){
        return $this->hasMany(BuyerChannel::class);
    }

    public function pandaChannels(){
        return $this->hasMany(BuyerPandaChannel::class);
    }

    public function platChannels(){
        return $this->hasMany(BuyerPlatChannel::class);
    }
}
