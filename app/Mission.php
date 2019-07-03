<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mission extends Model
{
    //

    protected $fillable = [
        'titre', 'descrip','nb_acts_ori','commentaire', 'date_deb','date_fin', 'statut_courant','realisee','affichee', 'dossier_id','type_Mission', 
        'user_id','assistant','url_doc_gen','equipement' , 'voiture', 'prestataire','intervenant','created_at','updated_at',
        'deleted_at',
        'type_heu_spec',
        'rdv','act_rdv','h_rdv',
        'dep_pour_miss','act_dep_pour_miss','h_dep_pour_miss',
        'dep_charge_dest','act_dep_charge_dest','h_dep_charge_dest',
        'arr_prev_dest','act_arr_prev_dest','h_arr_prev_dest',
        'decoll_ou_dep_bat','act_decoll_ou_dep_bat','h_decoll_ou_dep_bat',
        'arr_av_ou_bat','act_arr_av_ou_bat','h_arr_av_ou_bat',
        'retour_base','act_retour_base','h_retour_base'
    ];


public function dossier()
    {
        return $this->belongsTo('App\Dossier');
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
        return $this->hasMany('App\ActionEC');
    }

     public function activeActionEC()
    {
        return $this->hasMany('App\ActionEC')->where('statut','active')->orderBy('ordre');
    }                                                                   
 

}
