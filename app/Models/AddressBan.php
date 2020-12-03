<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddressBan extends Model
{
    const TYPE_IP_ADDRESS = 'ip-address';
    const TYPE_HOSTNAME = 'hostname';
    
    protected $guarded = [];
}
