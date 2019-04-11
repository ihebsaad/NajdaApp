<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeAction extends Model
{
    //
    protected $table='type_actions';
     protected $fillable = [
        
      'nom_type_action','etape1','ordre_etape1','etape2','ordre_etape2','etape3','ordre_etape3',
      'etape4','ordre_etape4','etape5','ordre_etape5','etape6','ordre_etape6',
      'etape7','ordre_etape7','etape8','ordre_etape8','etape9','ordre_etape9','etape10','ordre_etape10',
       'created_at','updated_at','deleted_at'

    ];
}
