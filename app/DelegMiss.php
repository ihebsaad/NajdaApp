<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DelegMiss extends Model
{
    //

     protected $table='Deleg_Miss';

    protected $fillable = [
       'util_affecteur','util_affecte','id_mission','id_dossier','id_seance','date_affectation','statut', 'created_at','updated_at','deleted_at'
    ];



}
