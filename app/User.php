<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;
use Illuminate\Support\Facades\Cache;
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','boite','passboite','statut','lastname','observation','user_type','signature','signature_en','username',
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
        return $this->hasMany('App\Mission')->orderBy('updated_at','desc');
       
    }

    public function dossier()
    {
        return $this->hasMany ("App\Dossier");
    }

    public function Actions()
    {
        return $this->hasMany('App\Action');
    }

     public function ActionECs()
    {
        return $this->hasMany('App\ActionEC');
    }

    /* public function ActionsTh()
    {
        return $this->hasManyThrough('App\Action', 'App\Action');
    }*/
   
     public function notes()
    {
        
       return $this->hasMany('App\Note')->where('statut','active');

        // return  $notes;
    }


    public function isOnline()
    {
        return Cache::has('user-online-'.$this->id);
    }
}
