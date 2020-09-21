<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Rubrique extends Model
{
	
protected $fillable = ['garantie','nom','montant','devise','commentaire'];
	
}