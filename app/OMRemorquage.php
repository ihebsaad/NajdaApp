<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OMRemorquage extends Model
{
    //
    protected $table='om_remorquage';


    protected $fillable = [

        'id','dossier','mission','action','titre','emplacement','parent','dernier','complete','prestataire_remorquage','CL_km_approximatif','CL_tarif','clientIMA','CL_heuredateRDV','CL_Dimanche','CL_Ferie','CL_Nuit','subscriber_name','subscriber_lastname','reference_medic','vehicule_type','vehicule_immatriculation','CL_etat_vehicule','CL_automatique_normal','CL_heure_RDV','CL_contacttel','CL_qualite','CL_lieuprest_pc','CL_prestatairetel_pc','CL_lieudecharge_dec','CL_prestatairetel_dec','CB_preetape','CL_lieupre','CB_trpersonne','CB_traccord','CL_infospersonne','CB_pregoulette','CL_apdestor','CL_nombateau','CL_port','CL_bateau','CL_heure_D','CB_prerades','CL_type_rapatriement','CL_heurearr','CL_remarque','client_dossier','CL_datedemande','CL_heuredemande','reference_medic2','reference_customer','supervisordate','remispardate','recuperepardate','dateheuredep','prehotel','dateheuredispprev','dhretbaseprev','idvehic','lvehicule','cartecarburant','cartetelepeage','lchauff','heuressup','dhdepartmiss','dharrivelieu','dhdepartlieu','dharrivedest','dhdepbase','dharrbase','duremiss','km_depart','km_arrive','km_distance','affectea','emispar','agent','type'


    ];
}