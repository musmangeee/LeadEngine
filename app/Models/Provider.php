<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Provider extends Model
{
    protected $guarded = [];

    public function deliverySchedules(){
        return $this->hasMany(ProviderDeliverySchedule::class);
    }

}
