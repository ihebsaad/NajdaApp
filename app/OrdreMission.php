<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OrdreMission extends Model
{
    //
    protected $table='ordremissions';
    

     protected $fillable = [
        
      'id','iddossier','idaction','type'

    ];
}
