<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Notif extends Model

{
	
  protected $fillable = [
 'user','entree','refdossier','dossierid','statut','affiche','read_at','nomassure','reception','emetteur','sujet','type'

     ];


    protected $dateFormat = 'Y-m-d H:i:s';

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }


}
