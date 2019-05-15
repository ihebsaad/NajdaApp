<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];


    public function Missions()
    {
        return  $this-> hasMany("App\Mission");
    }

     public function activeMissions()
    {
        return $this->hasMany('App\Mission')->where('statut_courant','Active');
    }

    public function dossier()
    {

        return $this->hasMany ("App\Dossier");
    }

    public function Actions()
    {
        return $this->hasMany('App\Action');
    }

     public function ActionsTh()
    {
        return $this->hasManyThrough('App\Action', 'App\Action');
    }
   
}
