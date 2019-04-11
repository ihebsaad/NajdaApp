<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    //

    protected $fillable = [
        'titre', 'descrip', 'date_deb','date_fin', 'statut_courant', 'dossier','type_action', 
        'utilisateur','assistant','prestataire','intervenant','created_at','updated_at','deleted_at'
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
        return $this->hasMany('App\SousAction','action');
    }


}
