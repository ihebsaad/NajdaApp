<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Dossier extends Model

{
    protected $fillable = ['id', 'created_by', 'affecte',


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
'mail_abonne',
'email1',
'email2',
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
'vehicule_immatriculation',
'lieu_immobilisation',
'vehicule_address',
'vehicule_address2',
'vehicule_phone',
'observation',
'is_contract_validated',
'closed_at',
'is_transport',
'prixtotal',
'facture_tot',


    ];
/*
    protected $dateFormat = 'Y-m-d H:i';

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }
*/
 public function actions()
    {
        return $this->hasMany('App\Action','dossier');
    }
    
}
