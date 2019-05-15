<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action extends Model
{
    //
    protected $table='actions';

    protected $fillable = [
        'mission_id','type_Mission','titre', 'descrip', 'date_deb','date_fin','date_report', 'ordre', 'realisee','statut','user_id','assistant_id','comment1','comment2','comment3','action_ava','action_apr', 'created_at','updated_at','deleted_at'
    ];

  public function Mission()
    {
        return $this->belongsTo('App\Mission');
    }


 
}
