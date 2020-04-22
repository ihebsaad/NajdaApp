<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OMMedicEquipement extends Model
{
    //
    protected $table='ommedic_equipements';


    protected $fillable = [

        'idom','idequipement','type','CL_date_heure_departmission','CL_date_heure_arrivebase'

    ];
}
