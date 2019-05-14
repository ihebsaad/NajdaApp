<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EtapeTypeMission extends Model
{
    //
     protected $table='etapes_type_mission'; 
     protected $fillable = [
        
          'titre' , 'ordre','type_Mission_id','etape_preced','etape_suiv','created_at','updated_at','deleted_at'

    ];


}
