<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EnvoyerNote extends Model
{
    //

     protected $table='envoy_notes';

    protected $fillable = [
       'util_affecteur','util_affecte','note_id','id_dossier','id_seance','date_rappel','date_affectation','statut', 'created_at','updated_at','deleted_at'
    ];



}
