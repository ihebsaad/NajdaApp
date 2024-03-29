<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
 use App\Attachement ;


class Entree extends Model
{
    //
    protected $fillable = ['emetteur','sujet','sujet2','contenu','contenutxt','nb_attach','reception','duration','type','dossier','statut','mailid','mission_id','affecte','notif','viewed','boite','destinataire','dossierid','commentaire','accuse','par','path'];

// boite = 0 reception , 1 = envoi

    public function attachements()
    {
        return $this->hasMany('App\Attachement');
    }
/*
  protected $dateFormat = 'Y-m-d H:i';

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }

*/

}
