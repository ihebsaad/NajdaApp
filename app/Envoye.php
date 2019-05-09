<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Envoye extends Model
{
    //
    protected $fillable = ['emetteur','sujet','contenu','destinataire','nb_attach' ,'par','statut','cc','cci','type','reception','boite' ,'dossier'];
// boite = 0 reception , 1 = envoi
    public function attachements()
    {
        return $this->hasMany('App\Attachement');
    }

  //  protected $dateFormat = 'Y-m-d H:i';

 /*   public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }
*/
}
