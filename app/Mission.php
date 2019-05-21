<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    //

    protected $fillable = [
        'titre', 'descrip','commentaire', 'date_deb','date_fin', 'statut_courant','realisee', 'dossier_id','type_Mission', 
        'user_id','assistant','prestataire','intervenant','created_at','updated_at','deleted_at'
    ];


public function dossier()
    {
        return $this->belongsTo('App\Dossier');
    }

 public function typeMission()
    {
        return $this->belongsTo('App\TypeMission');
    }

    public function Actions()
    {
        return $this->hasMany('App\Action');
    }

     public function activeAction()
    {
        return $this->hasMany('App\Action')->where('statut','Active');
    }
 

}
