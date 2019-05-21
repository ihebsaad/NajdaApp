<?php

namespace App;
use Illuminate\Database\Eloquent\Model;
class Boite extends Model
{
    protected $fillable = ['emetteur','sujet','contenu','nb_attach','statut','mailid','viewed','user','reception'];
/*
    protected $dateFormat = 'Y-m-d H:i';

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }
*/
}
