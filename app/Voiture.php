<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Voiture extends Model

{
	
  protected $fillable = [
 'name',
 'date_deb_indisponibilite',
 'date_fin_indisponibilite',
 'annule',
 'type',
 'km',
 'immarticulation'
     ];
 
}
