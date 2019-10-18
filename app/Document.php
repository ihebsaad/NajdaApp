<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    //
    protected $fillable = ['id','dossier','titre','description','emplacement','template','valchamps','parent','dernier','idtaggop','montantgop'];


}
