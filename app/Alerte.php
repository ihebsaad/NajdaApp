<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Alerte extends Model

{
	
  protected $fillable = [
 'statut',
 'id_dossier',
 'ref_dossier',
 'facture',
 'traite'
  ];

  /*protected $dateFormat = 'Y-m-d H:i:s';

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    } */
 
}
