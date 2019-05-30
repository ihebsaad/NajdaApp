<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Note extends Model
{



 protected $table='notes';

    protected $fillable = [
           'titre', 'contenu', 'date_rappel','user_id', 'lue','affichee','type','created_at','updated_at','deleted_at'
    ];

  public function utilisateur()
    {
        return $this->belongsTo('App\User');
    }

}
