<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Dossier extends Model

{
    protected $fillable = ['id', 'created_by', 'affecte','created','created_at',


'subscriber_name',
'subscriber_lastname',
'adresse_etranger',
'beneficiaire',
'prenom_benef',
'subscriber_phone_cell',
'subscriber_phone_domicile',
'subscriber_phone_home',
'subscriber_phone_4',
'to',
'to_guide',
'to_phone',
'initial_arrival_date',
'departure',
'destination',
'subscriber_local_address',
'subscriber_local_address_ch',
'tel_chambre',
'subscriber_mail1',
'subscriber_mail2',
'subscriber_mail3',
'type_dossier',
'type_affectation',
'current_status',
'opened_by_date',
'reference_medic',
'complexite',
'montant_tot',
'customer_id',
'reference_customer',
'tel_chambrefax',
'adresse_facturation',
'mail',
'franchise',
'montant_franchise',
'is_hospitalized',
'hospital_address',
'hospital_ch',
'hospital_phone',
'medecin_traitant',
'hospital_address2',
'hospital_ch2',
'hospital_phone2',
'medecin_traitant2',
'hospital_address3',
'hospital_ch3',
'hospital_phone3',
'medecin_traitant3',
        'vehicule_type',
        'vehicule_marque',
'vehicule_immatriculation',
'lieu_immobilisation',
'vehicule_address',
'vehicule_address2',
'vehicule_phone',
  'observation',
  'observation2',
  'observation3',
  'observation4',
'is_contract_validated',
'closed_at',
'is_transport',
'prixtotal',
'facture_tot',
'benefdiff',
'parente',
'parente2',
'parente3',
'beneficiaire2',
'beneficiaire3',
'prenom_benef2',
'prenom_benef3',

        'subscriber_local_address2',
        'subscriber_local_address3',
        'hotel',
        'ville',

        'empalcement_medic',
        'date_debut_medic',
        'date_fin_medic',
        'empalcement_medic2',
        'date_debut_medic2',
        'date_fin_medic2',
        'empalcement_medic3',
        'date_debut_medic3',
        'date_fin_medic3',

        'empalcement_trans',
        'date_debut_trans',
        'date_fin_trans',
        'empalcement_trans2',
        'date_debut_trans2',
        'date_fin_trans2',
        'empalcement_trans3',
        'date_debut_trans3',
        'date_fin_trans3',

        'type_trans',
        'type_trans2',
        'type_trans3'

    ];
/*
    protected $dateFormat = 'Y-m-d H:i:s';

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }
*/
/*
 public function activeMissions()
    {
        return $this->hasMany('App\Mission')->where('statut_courant','active');
    }
*/

 public function Missions()
    {
        return $this->hasMany('App\Mission');
    }
}
