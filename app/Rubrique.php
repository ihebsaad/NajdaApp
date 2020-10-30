<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Rubrique extends Model
{
	
protected $fillable = ['garantie','rubriqueinitial','nom','montant','devise','commentaire'];
	
}
