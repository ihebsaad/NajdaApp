<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ActionRappel extends Model
{
    //
    protected $table='actionrappels';

    protected $fillable = [
        'action_id','mission_id','type_Mission','titre', 'descrip', 'date_deb','date_fin','igno_ou_non','rapl_ou_non',
        'num_rappel','objetRappel','doc_rapp_ou_non',
        'date_rappel', 'report_ou_non','date_report', 'ordre', 'realisee','statut','nb_opt','opt_choisie','user_id',
        'assistant_id','comment1','comment2','comment3','action_ava','action_apr', 'created_at','updated_at','deleted_at'
    ];

  public function Mission()
    {
        return $this->belongsTo('App\Mission');
    }


 
}
