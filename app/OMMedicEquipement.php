<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OMMedicEquipement extends Model
{
    //
    protected $table='ommedic_equipements';


    protected $fillable = [

        'idom','idequipement','type'


    ];
}