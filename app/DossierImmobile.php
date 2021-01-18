<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DossierImmobile extends Model
{
    // dossiers immobiles plus de 3 jours 

      protected $table='dossierimmobiles';

    protected $fillable = [
        'dossier_id','reference_doss','nom_assure', 'ref_client','client_id','client_name','client_adresse','langue_client','mail_auto_envoye','reponse_client','date_envoi_mail','updatedmiss_at','remarques','created_at','updated_at','deleted_at'
    ];

//ALTER TABLE `dossierimmobiles` ADD `ref_client` VARCHAR(250) NULL AFTER `reference_doss`;
    /*protected $dateFormat = 'Y-m-d H:i:s';

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }*/ 


}