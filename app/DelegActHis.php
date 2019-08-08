<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DelegActHis extends Model
{
    //
    //
    protected $table='deleg_act_his';



    protected $fillable = [
       'util_affecteur','util_affecte','id_action','id_mission','id_dossier','id_seance','date_affectation','statut', 'created_at','updated_at','deleted_at'
    ];

  
   public function Mission()
    {
        return $this->belongsTo('App\Mission');
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
