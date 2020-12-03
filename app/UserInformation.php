<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserInformation extends Model
{
    protected $fillable = ['job_title','work_phone','ext','mobile_phone'];
    protected $table = 'user_informations';
}
