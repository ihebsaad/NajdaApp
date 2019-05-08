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


    public function actions()
    {
        return  $this-> hasMany("App\Action");
    }

     public function activeActions()
    {
        return $this->hasMany('App\Action')->where('statut_courant','Active');
    }

    public function dossier()
    {

        return $this->hasMany ("App\Dossier");
    }

    public function sousactions()
    {
        return $this->hasMany('App\SousAction');
    }

     public function sousactionsTh()
    {
        return $this->hasManyThrough('App\SousAction', 'App\Action');
    }
   
}
