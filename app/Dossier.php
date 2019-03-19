<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dossier extends Model
{
    //
    protected $fillable = ['ref','type','affecte'];

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }

}
