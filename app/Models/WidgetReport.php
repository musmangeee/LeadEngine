<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class WidgetReport extends Model
{
    protected $guarded= [];
    protected $appends = ['formatted_date'];

    public function getFormattedDateAttribute()
    {
        return Carbon::createFromFormat('Y-m-d', $this->date)->format('m/d');
    }
}
