<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Prestation extends Model

{
    protected $fillable = ['prestataire_id','type_prestations_id','dossier_id'
        ,'nb_km','price','prce_invoiced','date_prestation','annule','voiture_id','personne_id',
'annule',
'infirmier1_id',
'infirmier2_id',
'clos',
'marge',
'par',
'newprice',
'gouvernorat',
'specialite',
 'prestation',
 'statut',
 'prestation',
  'autorise',
'details',
'effectue',
'ville',
'oms_docs',
'facture'

];

}
