<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Facture extends Model

{
	
  protected $fillable = [

	'par',	'date_arrive',	'mois',	'client',	'reference',	'date_valid',	'date_facture',	'date_reception',	
	'date_scan',	'date_email',	'date_bord',	'date_poste',	'delai_email',	'delai_poste',
	  'iddossier','facture_prestataire','prestataire','honoraire','regle','mail_30_env','mail_45_env','mail_60_env'
     ];
	 
	 
 
}
