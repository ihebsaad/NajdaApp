<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Note extends Model
{


 protected $table='notes';

    protected $fillable = [
           'titre', 'contenu', 'statut','date_rappel','user_id','emetteur_id','originUser_id','lue','affichee','type','created_at','updated_at','deleted_at'
    ];

  public function utilisateur()
    {
        return $this->belongsTo('App\User');
    }

     public function emetteur()
    {
        return $this->belongsTo('App\User','emetteur_id');
    }

      public function originUser()
    {
        return $this->belongsTo('App\User','originUser_id');
    }

}
