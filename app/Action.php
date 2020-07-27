<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    //
    protected $table='actions';

    protected $fillable = [
        'mission_id','mission_id_org','id_type_miss','type_Mission','action_idt','titre', 'descrip', 'date_deb','date_fin','igno_ou_non','rapl_ou_non',
        'num_rappel',
        'rapp_doc_ou_non','date_rappel', 'report_ou_non','num_report','date_report', 'ordre', 'realisee','statut','nb_opt','opt_choisie','activ_avec_miss','user_id',
        'assistant_id','comment1','comment2','comment3','action_ava','action_apr', 'created_at','updated_at','deleted_at','duree','duree_eff'
    ];

  public function Mission()
    {
        return $this->belongsTo('App\MissionHis');
    }

      public function agent()
    {
        return $this->belongsTo('App\User','user_id');
    }
        public function assistant()
    {
        return $this->belongsTo('App\User','assistant_id');
    }


 
}
