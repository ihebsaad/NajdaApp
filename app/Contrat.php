<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Contrat extends Model

{

    protected $fillable = [
        'nom',
		'type'
    ];
}
