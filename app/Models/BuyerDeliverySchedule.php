<?php

namespace App\Models;

use App\Models\Buyer;
use Illuminate\Database\Eloquent\Model;

class BuyerDeliverySchedule extends Model
{
    protected $guarded = [];

    public function buyer(){
        return $this->hasOne(Buyer::class);
    }

    public function scopeCurrentCaps($query){
        $timeAccess = now()->format('H:i:s');
        $dayOfWeek = now()->format('l');
        return $query->where('day_of_week',$dayOfWeek)->whereTime('start_at','<=',$timeAccess)->whereTime('end_at','>=',$timeAccess);
    }
}
