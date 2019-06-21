<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Intervenant extends Model

{
	
  protected $fillable = [
      'nom',
      'dossier',
 'prenom',
 'date',
 'type_prestation',
 'gouvernorat',
 'specialite',
 'email',
 'tel',
 'statut',
      ];
 
}
