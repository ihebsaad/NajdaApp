<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
    //
    protected $fillable = ['id','ref','type','affecte','abonnee'];

    public function getDateFormat()
    {
        return 'Y-m-d H:i';
    }

}
