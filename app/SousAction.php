<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SousAction extends Model
{
    //
    protected $table='sous_actions';

    protected $fillable = [
        'action','titre', 'descrip', 'date_deb','date_fin', 'ordre', 'realisee','commentaire','s_action_ava','s_action_apr', 'created_at','updated_at','deleted_at'
    ];

  public function action()
    {
        return $this->belongsTo('App\Action');
    }
 
}
