<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Equipement extends Model

{
	
  protected $fillable = [
 'nom',
 'reference',
 'numero',
 'date_deb_indisponibilite',
 'date_fin_indisponibilite',
 'annule',
        ];
 
}
