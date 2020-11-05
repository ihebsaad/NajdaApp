<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Garantie extends Model
{
	
protected $fillable = [ 'nom','description','montantgr','devisegr'];
	
}
