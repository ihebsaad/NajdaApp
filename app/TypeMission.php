<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeMission extends Model
{
    //
    protected $table='type_mission';
     protected $fillable = [
        
      'nom_type_Mission','etape1','ordre_etape1','desc_etape1','etape2','ordre_etape2','desc_etape2','etape3','ordre_etape3'
      ,'desc_etape3',
      'etape4','ordre_etape4','desc_etape4','etape5','ordre_etape5','desc_etape5','etape6','ordre_etape6','desc_etape6',
      'etape7','ordre_etape7','desc_etape7','etape8','ordre_etape8','desc_etape8','etape9','ordre_etape9','desc_etape9',
      'etape10','ordre_etape10','desc_etape10', 'created_at','updated_at','deleted_at'

    ];
}
