<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Note_his extends Model
{


 protected $table='notes_his';

    protected $fillable = [
           'titre', 'contenu', 'statut','note_id','date_rappel','user_id','emetteur_id','originUser_id','lue','affichee','type','nommission','villemission','created_at','updated_at','deleted_at'
    ];

  public function utilisateur()
    {
        return $this->belongsTo('App\User');
    }

}