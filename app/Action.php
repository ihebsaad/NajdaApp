<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    //

    protected $fillable = [
        'titre', 'descrip', 'date_deb','date_fin', 'statut_courant','realisee', 'dossier_id','type_action', 
        'user_id','assistant','prestataire','intervenant','created_at','updated_at','deleted_at'
    ];


public function dossier()
    {
        return $this->belongsTo('App\Dossier');
    }

 public function typeaction()
    {
        return $this->belongsTo('App\TypeAction');
    }

    public function sousactions()
    {
        return $this->hasMany('App\SousAction');
    }

     public function activeSousAction()
    {
        return $this->hasMany('App\SousAction')->where('statut','Active');
    }
 

}
