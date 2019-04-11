<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EtapeTypeAction extends Model
{
    //
     protected $table='etapes_type_actions'; 
     protected $fillable = [
        
          'titre' , 'ordre','type_action','created_at','updated_at','deleted_at'

    ];


}
