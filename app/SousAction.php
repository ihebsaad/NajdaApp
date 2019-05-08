<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SousAction extends Model
{
    //
    protected $table='sous_actions';

    protected $fillable = [
        'action_id','type_action','titre', 'descrip', 'date_deb','date_fin','date_report', 'ordre', 'realisee','statut','user_id','assistant_id','comment1','comment2','comment3','s_action_ava','s_action_apr', 'created_at','updated_at','deleted_at'
    ];

  public function action()
    {
        return $this->belongsTo('App\Action');
    }


 
}
