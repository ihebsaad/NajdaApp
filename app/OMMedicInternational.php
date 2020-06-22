<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OMMedicInternational extends Model
{
    //
    protected $table='om_medicinternationnal';


    protected $fillable = [

        'id','dossier','mission','action','titre','emplacement','parent','dernier','complete','prestataire_medic','id_prestataire','CL_Concentrateur_O2','CL_lotadlsimple','CL_lotadlrenforce','CL_lotadlcomplet','reference_medic','reference_customer','CL_clientorigine','subscriber_name','subscriber_lastname','CL_age','CL_clientoriginedossier','CL_personnedemande','CL_telclientorigine','lieu_immobilisation','CL_date_heure_departmission','CL_date_decollage','CL_date_heure_prise','CL_date_heure_departclinique','CL_chaise','CL_vol','CL_lieu_dec','CL_date_heure_decollage','CL_arrive','CL_ambulance_taxi','CL_depose','CL_accepte','CL_hotel_aeroport','CL_prendre_vol','CL_date_heure_decollage2','CL_date_retour','CL_heure_taxi','CL_achimineaeroport','CL_volee','CL_decollevers','CL_heure_dec','CL_arrivea','CL_heure_arrive','CL_date_heure_arrivebase','CL_date_heure_retourbase','CL_date_heure_missiondepart','agent'


    ];
}
