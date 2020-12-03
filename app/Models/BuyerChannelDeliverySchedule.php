<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuyerChannelDeliverySchedule extends Model
{
    protected $guarded = [];

    public function buyerChannel(){
        return $this->hasOne(BuyerChannel::class);
    }

    public function scopeCurrentCaps($query){
        $timeAccess = now()->format('H:i:s');
        $dayOfWeek = now()->format('l');
        return $query->where('day_of_week',$dayOfWeek)->whereTime('start_at','<=',$timeAccess)->whereTime('end_at','>=',$timeAccess);
    }
}
