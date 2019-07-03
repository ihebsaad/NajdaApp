<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DelegMissHis extends Model
{
    //

     protected $table='Deleg_Miss_his';

    protected $fillable = [
       'util_affecteur','util_affecte','id_mission','id_dossier','id_seance','date_affectation','statut', 'created_at','updated_at','deleted_at'
    ];



}