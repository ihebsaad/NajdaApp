<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AffectDoss extends Model
{
    //
    protected $table='affect_doss';

    protected $fillable = [
       'util_affecteur','util_affecte','id_seance','id_dossier','date_affectation', 'created_at','updated_at','deleted_at'
    ];

     


 
}
