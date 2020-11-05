<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EmailAuto extends Model
{
    //

    protected $table='emails_auto';

    protected $fillable = [ 'emetteur', 'destinataire','cc', 'sujet', 'contenu', 'contenutxt',  'created_at', 'updated_at',  'deleted_at', 'type',  'dossier', 'dossierid', 'factureid','client','prestataire', 'facture_ref', 'commentaire', 'accuse'];

// boite = 0 reception , 1 = envoi
   

  //  protected $dateFormat = 'Y-m-d H:i';

 /*   public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }
*/
}
