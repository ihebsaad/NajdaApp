<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Specialite extends Model

{

    protected $fillable = [
        'nom',
        'type_prestation'
    ];
}
