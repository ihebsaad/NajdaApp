<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Envoye extends Model
{
    //
    protected $fillable = ['emetteur','client','sujet','contenu','destinataire','nb_attach' ,'par','statut','cc','cci','type','reception','duration','boite' ,'dossier','description','commentaire','path'];
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
