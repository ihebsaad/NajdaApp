<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Attachement extends Model
{
    //
    protected $fillable = ['id','nom','type','entree_id','path','facturation','envoye_id','parent','boite','dossier','description'];

}
