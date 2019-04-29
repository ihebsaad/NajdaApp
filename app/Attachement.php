<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachement extends Model
{
    //
    protected $fillable = ['id','nom','type','entree_id','path','facturation','envoye_id','parent','boite'];

    public function getDateFormat()
    {
        return 'Y-m-d H:i:s.u';
    }

}
