<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entree extends Model
{
    //
    protected $fillable = ['emetteur','sujet','contenu','nb_attach','reception','type','dossier','statut','updated_at','mailid','affecte','notif','viewed'];

   /*protected $dateFormat = 'Y-m-d H:i';

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }*/
}
