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
      'arr_av_ou_bat','act_arr_av_ou_bat','retour_base', 'act_retour_base','sejour_deb','act_sejour_deb','sejour_fin','act_sejour_fin','location_voit_deb','act_location_voit_deb','location_voit_fin','act_location_voit_fin', 'nb_acts',


    'action1','duree1','ordre_action1','desc_action1', 'nb_opt_act1','igno_ou_non1','rapl_ou_non1','report_ou_non1',
      'doc_rapp_ou_non1','activ_avec_miss1',
     'action2','duree2','ordre_action2','desc_action2', 'nb_opt_act2','igno_ou_non2','rapl_ou_non2','report_ou_non2',
      'doc_rapp_ou_non2','activ_avec_miss2',
    'action3','duree3','ordre_action3','desc_action3', 'nb_opt_act3','igno_ou_non3','rapl_ou_non3','report_ou_non3',
      'doc_rapp_ou_non3','activ_avec_miss3',
    'action4','duree4','ordre_action4','desc_action4', 'nb_opt_act4','igno_ou_non4','rapl_ou_non4','report_ou_non4',
      'doc_rapp_ou_non4','activ_avec_miss4',
    'action5','duree5','ordre_action5','desc_action5', 'nb_opt_act5','igno_ou_non5','rapl_ou_non5','report_ou_non5',
      'doc_rapp_ou_non5','activ_avec_miss5',
    'action6','duree6','ordre_action6','desc_action6', 'nb_opt_act6','igno_ou_non6','rapl_ou_non6','report_ou_non6',
      'doc_rapp_ou_non6','activ_avec_miss6',
    'action7','duree7','ordre_action7','desc_action7', 'nb_opt_act7','igno_ou_non7','rapl_ou_non7','report_ou_non7',
      'doc_rapp_ou_non7','activ_avec_miss7',
    'action8','duree8','ordre_action8','desc_action8', 'nb_opt_act8','igno_ou_non8','rapl_ou_non8','report_ou_non8',
      'doc_rapp_ou_non8','activ_avec_miss8',
    'action9','duree9','ordre_action9','desc_action9', 'nb_opt_act9','igno_ou_non9','rapl_ou_non9','report_ou_non9',
      'doc_rapp_ou_non9','activ_avec_miss9',
    'action10','duree10','ordre_action10','desc_action10', 'nb_opt_act10','igno_ou_non10','rapl_ou_non10','report_ou_non10','doc_rapp_ou_non10','activ_avec_miss10',


    'action11','duree11','ordre_action11','desc_action11', 'nb_opt_act11','igno_ou_non11','rapl_ou_non11','report_ou_non11', 'doc_rapp_ou_non11','activ_avec_miss11',
     'action12','duree12','ordre_action12','desc_action12', 'nb_opt_act12','igno_ou_non12','rapl_ou_non12','report_ou_non12','doc_rapp_ou_non12','activ_avec_miss12',
    'action13','duree13','ordre_action13','desc_action13', 'nb_opt_act13','igno_ou_non13','rapl_ou_non13','report_ou_non13', 'doc_rapp_ou_non13','activ_avec_miss13',
    'action14','duree14','ordre_action14','desc_action14', 'nb_opt_act14','igno_ou_non14','rapl_ou_non14','report_ou_non14','doc_rapp_ou_non14','activ_avec_miss14',
    'action15','duree15','ordre_action15','desc_action15', 'nb_opt_act15','igno_ou_non15','rapl_ou_non15','report_ou_non15', 'doc_rapp_ou_non15','activ_avec_miss15',
    'action16','duree16','ordre_action16','desc_action16', 'nb_opt_act16','igno_ou_non16','rapl_ou_non16','report_ou_non16','doc_rapp_ou_non16','activ_avec_miss16',
    'action17','duree17','ordre_action17','desc_action17', 'nb_opt_act17','igno_ou_non17','rapl_ou_non17','report_ou_non17','doc_rapp_ou_non17','activ_avec_miss17',
    'action18','duree18','ordre_action18','desc_action18', 'nb_opt_act18','igno_ou_non18','rapl_ou_non18','report_ou_non18','doc_rapp_ou_non18','activ_avec_miss18',
    'action19','duree19','ordre_action19','desc_action19', 'nb_opt_act19','igno_ou_non19','rapl_ou_non19','report_ou_non19','doc_rapp_ou_non19','activ_avec_miss19',
    'action20','duree20','ordre_action20','desc_action20', 'nb_opt_act20','igno_ou_non20','rapl_ou_non20','report_ou_non20','doc_rapp_ou_non20','activ_avec_miss20',

    'action21','duree21','ordre_action21','desc_action21', 'nb_opt_act21','igno_ou_non21','rapl_ou_non21','report_ou_non21','doc_rapp_ou_non21','activ_avec_miss21',
     'action22','duree22','ordre_action22','desc_action22', 'nb_opt_act22','igno_ou_non22','rapl_ou_non22','report_ou_non22','doc_rapp_ou_non22','activ_avec_miss22',
    'action23','duree23','ordre_action23','desc_action23', 'nb_opt_act23','igno_ou_non23','rapl_ou_non23','report_ou_non23','doc_rapp_ou_non23','activ_avec_miss23',
    'action24','duree24','ordre_action24','desc_action24', 'nb_opt_act24','igno_ou_non24','rapl_ou_non24','report_ou_non24','doc_rapp_ou_non24','activ_avec_miss24',
    'action25','duree25','ordre_action25','desc_action25', 'nb_opt_act25','igno_ou_non25','rapl_ou_non25','report_ou_non25','doc_rapp_ou_non25','activ_avec_miss25',
    'action26','duree26','ordre_action26','desc_action26', 'nb_opt_act26','igno_ou_non26','rapl_ou_non26','report_ou_non26', 'doc_rapp_ou_non26','activ_avec_miss26',
    'action27','duree27','ordre_action27','desc_action27', 'nb_opt_act27','igno_ou_non27','rapl_ou_non27','report_ou_non27','doc_rapp_ou_non27','activ_avec_miss27',
    'action28','duree28','ordre_action28','desc_action28', 'nb_opt_act28','igno_ou_non28','rapl_ou_non28','report_ou_non28','doc_rapp_ou_non28','activ_avec_miss28',
    'action29','duree29','ordre_action29','desc_action29', 'nb_opt_act29','igno_ou_non29','rapl_ou_non29','report_ou_non29','doc_rapp_ou_non29','activ_avec_miss29',
    'action30','duree30','ordre_action30','desc_action30', 'nb_opt_act30','igno_ou_non30','rapl_ou_non30','report_ou_non30','doc_rapp_ou_non30','activ_avec_miss30',

       'created_at','updated_at','deleted_at'

    ];
}
