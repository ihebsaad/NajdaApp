<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    //
     protected $table='missions';

    protected $fillable = [
        'titre', 'descrip','miss_mere_id','nb_acts_ori','commentaire', 'date_deb','date_fin', 'statut_courant','realisee','affichee', 'dossier_id','nom_type_miss','type_Mission', 'origin_id',
        'user_id','assistant_id','emetteur_id','id_entree','url_doc_gen','equipement' , 'voiture', 'prestataire','intervenant','created_at','updated_at',
        'deleted_at','om_in_ex',
        'type_heu_spec','type_heu_spec_archiv','date_spec_affect','date_spec_affect2','date_spec_affect3',
        'rdv','act_rdv','h_rdv',
        'dep_pour_miss','act_dep_pour_miss','h_dep_pour_miss',
        'dep_charge_dest','act_dep_charge_dest','h_dep_charge_dest',
        'arr_prev_dest','act_arr_prev_dest','h_arr_prev_dest',
        'decoll_ou_dep_bat','act_decoll_ou_dep_bat','h_decoll_ou_dep_bat',
        'arr_av_ou_bat','act_arr_av_ou_bat','h_arr_av_ou_bat',
        'retour_base','act_retour_base','h_retour_base','deb_sejour','h_deb_sejour','act_deb_sejour','fin_sejour',
        'h_fin_sejour','act_fin_sejour','deb_location_voit',
        'h_deb_location_voit','act_deb_location_voit','fin_location_voit','h_fin_location_voit','act_fin_location_voit','duree_eff'
    ];

   /* protected $dateFormat = 'Y-m-d H:i';

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }*/


public function dossier()
    {
        return $this->belongsTo('App\Dossier','dossier_id');
    }

 public function typeMission()
    {
        return $this->belongsTo('App\TypeMission','type_Mission');
    }

    public function Actions()
    {
        return $this->hasMany('App\Action');
    }

     public function activeAction()
    {
        return $this->hasMany('App\Action')->where('statut','active');
    }   

     public function ActionECs()
    {
        return $this->hasMany('App\ActionEC')->orderBy('ordre');
    }

    public function ActionECsSansRappel()
    {
        return $this->hasMany('App\ActionEC')->where('statut','!=','rfaite');
    }

     public function activeActionEC()
    {
        return $this->hasMany('App\ActionEC')->where('statut','active')->orderBy('ordre');
    }

      public function ActionEC_report_rappel()
    {
        return $this->hasMany('App\ActionEC')->where('statut','reportee')->orWhere('statut','rappelee')->orderBy('ordre');
    }

    public function agent()
    {
        return $this->belongsTo('App\User','user_id');
    }

        public function assistant()
    {
        return $this->belongsTo('App\User','assistant_id');
    }  

     public function user_origin()
    {
        return $this->belongsTo('App\User','origin_id');
    }

     public function emetteur()
    {
        return $this->belongsTo('App\User','emetteur_id');
    }






}
