<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PortfolioRouting extends Model
{
    use SoftDeletes;
    protected $guarded = [];


    protected function portfolio(){
        return $this->belongsTo(Portfolio::class);
    }

    public function routeable(){
        return $this->morphTo();
    }
}
