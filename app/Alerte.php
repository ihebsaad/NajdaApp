<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Alerte extends Model

{
	
  protected $fillable = [
 'statut',
 'id_dossier',
 'ref_dossier',
 'facture'
  ];
 
}
