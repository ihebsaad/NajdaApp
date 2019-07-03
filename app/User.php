<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Auth;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','boite','passboite'
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
        return $this->hasMany('App\Mission')->where('statut_courant','active');
    }

    public function dossier()
    {
        return $this->hasMany ("App\Dossier");
    }

    public function Actions()
    {
        return $this->hasMany('App\Action');
    }

    /* public function ActionsTh()
    {
        return $this->hasManyThrough('App\Action', 'App\Action');
    }*/
   
     public function notes()
    {

        //$currentDateTime = date('Y-m-d H:i:s');
        //dd($currentDateTime);

         $dtc = (new \DateTime())->modify('-1 Hour')->format('Y-m-d H:i:s');

         $notes=Note::where('date_rappel','<=', $dtc)->where('user_id', Auth::user()->id)->get();

         //var_dump($notes);

         foreach ($notes as $note) {

             Note::where('id',$note->id)->update(['affichee' => 1]);
            // dd($note);
            
         }

       return $this->hasMany('App\Note')->where('date_rappel','<=', $dtc);

        // return  $notes;
    }


}
