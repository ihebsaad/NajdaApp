<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OMAmbulance extends Model
{
    //
    protected $table='om_ambulance';


    protected $fillable = [

        'id','dossier','mission','action','titre','emplacement','parent','dernier','complete','prestataire_ambulance','CL_transfert','CL_km_approximatif','CL_tarif','clientIMA','CL_heuredateRDV','CL_Dimanche','CL_Ferie','CL_Nuit','CL_AllerRetour','subscriber_name','subscriber_lastname','reference_medic','CL_heure_RDV','CL_contacttel','CL_qualite','CL_type_ambulance','CL_O2','CL_respirateur','CL_couveuse','CL_pse','CL_nbpsemax','CL_chaise_roulante','CB_accompagnant','CL_nbreaccompagnant','CL_relation','CL_nbrebagages','CL_type','CB_escorte','CL_escorte','CL_nomprenom','CL_numtel','CL_autresinformations','CL_lieuprest_pc','CL_prestatairetel_pc','CL_lieudecharge_dec','CL_prestatairetel_dec','CB_preetape','CL_lieupre','CB_trmedecin','CL_infosmedecin','CB_preportaeroport','CL_destorg','CL_apdestor','CL_volorbateau','CL_decatter','CL_heure_D_A','CL_type_siege','CL_oxygene','CL_refbillet','CL_heurearr','CL_resumeclinique','CL_remarque','client_dossier','CL_datedemande','CL_heuredemande','reference_medic2','reference_customer','editepardate','supervisordate','remispardate','recuperepardate','dateheuredep','prehotel','dateheuredispprev','dhretbaseprev','lvehicule','vehicID','lmedecin','CL_rea','idparamed','lparamed','idambulancier1','lambulancier1','heuressup','idambulancier2','lambulancier2','heuressup2','dhdepartmiss','dharrivelieu','dhdepartlieu','dharrivedest','dhredepart','dh2emearrdest','dhdepbase','dharrbase','duremiss','km_depart','km_arrive','km_distance','affectea','emispar','agent','type','idprestation','statut'


    ];
}
