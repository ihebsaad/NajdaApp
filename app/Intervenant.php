<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Intervenant extends Model

{
	
  protected $fillable = [
      'dossier',
'prestataire_id',
      'nom',
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
