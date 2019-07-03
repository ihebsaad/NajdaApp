<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TypeMission extends Model
{
    //
    protected $table='type_mission';
    

     protected $fillable = [
        
      'nom_type_Mission','des_miss','type_heu_spec','rdv','act_rdv','dep_pour_miss','act_dep_pour_miss','dep_charge_dest',
      'act_dep_charge_dest','arr_prev_dest','act_arr_prev_dest','decoll_ou_dep_bat','act_decoll_ou_dep_bat',
      'arr_av_ou_bat','act_arr_av_ou_bat','retour_base', 'act_retour_base','nb_acts',


      'action1','ordre_action1','desc_action1', 'nb_opt_act1','igno_ou_non1','rapl_ou_non1','report_ou_non1',
      'doc_rapp_ou_non1','activ_avec_miss1',
      'action2','ordre_action2','desc_action2', 'nb_opt_act2','igno_ou_non2','rapl_ou_non2','report_ou_non2',
      'doc_rapp_ou_non2','activ_avec_miss2',
      'action3','ordre_action3','desc_action3', 'nb_opt_act3','igno_ou_non3','rapl_ou_non3','report_ou_non3',
      'doc_rapp_ou_non3','activ_avec_miss3',
      'action4','ordre_action4','desc_action4', 'nb_opt_act4','igno_ou_non4','rapl_ou_non4','report_ou_non4',
      'doc_rapp_ou_non4','activ_avec_miss4',
      'action5','ordre_action5','desc_action5', 'nb_opt_act5','igno_ou_non5','rapl_ou_non5','report_ou_non5',
      'doc_rapp_ou_non5','activ_avec_miss5',
       'action6','ordre_action6','desc_action6', 'nb_opt_act6','igno_ou_non6','rapl_ou_non6','report_ou_non6',
      'doc_rapp_ou_non6','activ_avec_miss6',
       'action7','ordre_action7','desc_action7', 'nb_opt_act7','igno_ou_non7','rapl_ou_non7','report_ou_non7',
      'doc_rapp_ou_non7','activ_avec_miss7',
       'action8','ordre_action8','desc_action8', 'nb_opt_act8','igno_ou_non8','rapl_ou_non8','report_ou_non8',
      'doc_rapp_ou_non8','activ_avec_miss8',
        'action9','ordre_action9','desc_action9', 'nb_opt_act9','igno_ou_non9','rapl_ou_non9','report_ou_non9',
      'doc_rapp_ou_non9','activ_avec_miss9',

       'created_at','updated_at','deleted_at'

    ];
}
