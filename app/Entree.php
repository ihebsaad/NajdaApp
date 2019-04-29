<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
 use App\Attachement ;


class Entree extends Model
{
    //
    protected $fillable = ['emetteur','sujet','contenu','nb_attach','reception','type','dossier','statut','mailid','affecte','notif','viewed'];


    public function attachements()
    {
        return $this->hasMany('App\Attachement');
    }

   protected $dateFormat = 'Y-m-d H:i';
/*
    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }
*/
}
