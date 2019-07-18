<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Demande extends Model

{

    protected $fillable = [
        'par',
        'vers',
        'role',
        'statut',
        'emetteur',
        'type',


    ];
}
