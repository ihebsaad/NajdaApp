<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OMTaxi extends Model
{
    //
    protected $table='om_taxi';
    

     protected $fillable = [
        
      	'id','dossier','mission','action','titre','emplacement','parent','dernier','complete','prestataire_taxi','CL_choix','CL_km_approximatif','CL_tarif','clientIMA','CL_heuredateRDV','CL_Dimanche','CL_Ferie','CL_Nuit','CL_AllerRetour','subscriber_name','subscriber_lastname','reference_medic','CL_heure_RDV','CL_contacttel','CL_qualite','CL_circuitdestination','CB_accompagnant','CL_nbreaccompagnant','CL_relation','CL_nbrebagages','CL_type','CL_passagermalade','CL_statutmalade','CL_chaiseroulante','CL_escorte','CL_nomprenom','CL_numtel','CL_lieuprest_pc','CL_prestatairetel_pc','CL_lieudecharge_dec','CL_prestatairetel_dec','CB_preetape','CL_lieupre','CB_trmedecin','CL_infosmedecin','CB_preportaeroport','CL_destorg','CL_apdestor','CL_volorbateau','CL_decatter','CL_heure_D_A','CL_bateau','CL_refbillet','CL_heurearr','CL_resumeclinique','CL_remarque','client_dossier','CL_datedemande','CL_heuredemande','reference_medic2','reference_customer','supervisordate','remispardate','recuperepardate','dateheuredep','prehotel','dateheuredispprev','dhretbaseprev','idvehic','lvehicule','cartecarburant','cartetelepeage','lchauff','heuressup','dhdepartmiss','dharrivelieu','dhdepartlieu','dharrivedest','dhredepart','dh2emearrdest','dhdepbase','dharrbase','duremiss','km_depart','km_arrive','km_distance','affectea','emispar','agent','type'


    ];
}
