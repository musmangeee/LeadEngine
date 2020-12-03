<?php

namespace App;

use App\Models\UserOption;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Passport\HasApiTokens;
use Yadahan\AuthenticationLog\AuthenticationLogable;

class User extends Authenticatable
{
    use Notifiable, HasRoles, HasApiTokens, AuthenticationLogable;

    const STATUS_ACTIVE = 'active';
    const STATUS_DISABLED = 'disabled';

    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
    * The attributes that should be cast to native types.
    *
    * @var array
    */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $guard_name = 'api';

    public function information()
    {
        return $this->hasOne('App\UserInformation');
    }

    public function disable()
    {
        $this->status = 'disabled';
        return $this->save();
    }

    public function activate()
    {
        $this->status = 'active';
        return $this->save();
    }

    public function receivesBroadcastNotificationsOn()
    {
        return 'App.User.'.$this->id;
    }

    public function options()
    {
        return $this->hasMany(UserOption::class);
    }


    public function broadcastMessages()
    {
        return $this->hasMany(Message::class);
    }

    public function accessTokens()
    {
        return $this->hasMany(OauthAccessToken::class);
    }
}
